<?php
session_start();
require_once "db.php";

/* =========================
   HELPER FUNCTION (TEXT → SCORE)
========================= */
function getScore($answer, $configJson) {
    if (empty($configJson) || empty($answer)) return null;
    $config = json_decode($configJson, true);
    if (!$config || !isset($config['options'])) return null;

    foreach ($config['options'] as $index => $opt) {
        if (is_string($opt)) {
            if (strtolower(trim($opt)) == strtolower(trim($answer))) {
                return $index + 1;
            }
        }
        if (is_array($opt) && isset($opt['text'], $opt['score'])) {
            if (strtolower(trim($opt['text'])) == strtolower(trim($answer))) {
                return $opt['score'];
            }
        }
    }
    return null;
}

/* =========================
   FILTERS
========================= */
$selected_year = $_GET['year'] ?? date('Y');
$selected_batch = $_GET['batch'] ?? 'all';
$selected_programme = $_GET['programme'] ?? 'all';

/* =========================
   GET FILTER OPTIONS
========================= */
$years = $pdo->query("SELECT DISTINCT YEAR(created_at) as year FROM surveys ORDER BY year DESC")->fetchAll(PDO::FETCH_COLUMN);
$batches = $pdo->query("SELECT DISTINCT batch_year FROM alumni_students WHERE batch_year IS NOT NULL ORDER BY batch_year DESC")->fetchAll(PDO::FETCH_COLUMN);
$programmes = $pdo->query("SELECT DISTINCT programme FROM alumni_students WHERE programme IS NOT NULL ORDER BY programme ASC")->fetchAll(PDO::FETCH_COLUMN);

/* =========================
   PEO-PLO MAPPING DATA
========================= */
$mappingStmt = $pdo->query("SELECT peo_code, plo_code FROM peo_plo_mapping WHERE peo_code IS NOT NULL AND plo_code IS NOT NULL");
$peoToPlo = [];
while ($row = $mappingStmt->fetch(PDO::FETCH_ASSOC)) {
    $peoToPlo[$row['peo_code']][] = $row['plo_code'];
}

$peoList = $pdo->query("SELECT DISTINCT peo_code FROM peo_plo_mapping WHERE peo_code IS NOT NULL AND peo_code != '' ORDER BY peo_code ASC")->fetchAll(PDO::FETCH_COLUMN);
$ploList = $pdo->query("SELECT DISTINCT plo_code FROM peo_plo_mapping WHERE plo_code IS NOT NULL AND plo_code != '' ORDER BY plo_code ASC")->fetchAll(PDO::FETCH_COLUMN);

/* =========================
   KPI CALCULATION FUNCTION
========================= */
function calculateAchievement($pdo, $year, $batch, $programme, $peoToPlo) {
    $sql = "
    SELECT 
        q.peo_id as peo_code, 
        q.question_type, 
        q.question_config,
        sa.score, 
        sa.answer_text
    FROM survey_answers sa
    JOIN survey_questions q ON sa.question_id = q.question_id
    JOIN survey_responses sr ON sa.response_id = sr.response_id
    JOIN surveys s ON sr.survey_id = s.survey_id
    JOIN alumni_students a ON sr.alumni_id = a.id
    WHERE YEAR(s.created_at) = ?
    ";
    $params = [$year];
    if ($batch !== 'all') { $sql .= " AND a.batch_year = ?"; $params[] = $batch; }
    if ($programme !== 'all') { $sql .= " AND a.programme = ?"; $params[] = $programme; }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $peoTemp = [];
    $ploTemp = [];
    $statsTemp = [];

    foreach ($data as $row) {
        $peo = $row['peo_code'];
        if (!$peo) continue;

        $score = null;
        if ($row['question_type'] == 'rating' || $row['question_type'] == 'scale') {
            $score = (int)$row['score'];
        } elseif ($row['question_type'] == 'checkbox') {
            $decoded = json_decode($row['answer_text'] ?? '[]', true);
            if (is_array($decoded)) {
                foreach ($decoded as $ans) {
                    $s = getScore($ans, $row['question_config']);
                    if ($s !== null) {
                        $peoTemp[$peo]['total'] = ($peoTemp[$peo]['total'] ?? 0) + $s;
                        $peoTemp[$peo]['count'] = ($peoTemp[$peo]['count'] ?? 0) + 1;
                        $statsTemp[$peo][] = $s;
                        if (isset($peoToPlo[$peo])) {
                            foreach ($peoToPlo[$peo] as $plo) {
                                $ploTemp[$plo]['total'] = ($ploTemp[$plo]['total'] ?? 0) + $s;
                                $ploTemp[$plo]['count'] = ($ploTemp[$plo]['count'] ?? 0) + 1;
                            }
                        }
                    }
                }
            }
            continue;
        } else {
            $score = getScore($row['answer_text'], $row['question_config']);
        }

        if ($score !== null && $score > 0) {
            $peoTemp[$peo]['total'] = ($peoTemp[$peo]['total'] ?? 0) + $score;
            $peoTemp[$peo]['count'] = ($peoTemp[$peo]['count'] ?? 0) + 1;
            $statsTemp[$peo][] = $score;
            
            if (isset($peoToPlo[$peo])) {
                foreach ($peoToPlo[$peo] as $plo) {
                    $ploTemp[$plo]['total'] = ($ploTemp[$plo]['total'] ?? 0) + $score;
                    $ploTemp[$plo]['count'] = ($ploTemp[$plo]['count'] ?? 0) + 1;
                }
            }
        }
    }

    $peoScores = [];
    foreach ($peoTemp as $peo => $val) {
        if ($val['count'] > 0) $peoScores[$peo] = round(($val['total'] / ($val['count'] * 5)) * 100);
    }

    $ploScores = [];
    foreach ($ploTemp as $plo => $val) {
        if ($val['count'] > 0) $ploScores[$plo] = round(($val['total'] / ($val['count'] * 5)) * 100);
    }

    $stats = [];
    foreach ($statsTemp as $peo => $scores) {
        $stats[] = [
            'peo_id' => $peo,
            'avg' => array_sum($scores) / count($scores),
            'min' => min($scores),
            'max' => max($scores)
        ];
    }
    usort($stats, function($a, $b) {
        return intval(filter_var($a['peo_id'], FILTER_SANITIZE_NUMBER_INT)) <=> intval(filter_var($b['peo_id'], FILTER_SANITIZE_NUMBER_INT));
    });

    return ['peo' => $peoScores, 'plo' => $ploScores, 'stats' => $stats];
}

$currentData = calculateAchievement($pdo, $selected_year, $selected_batch, $selected_programme, $peoToPlo);
$prevData = calculateAchievement($pdo, $selected_year - 1, $selected_batch, $selected_programme, $peoToPlo);

/* =========================
   TREND DATA
======================== */
$trendDataRaw = $pdo->query("
    SELECT YEAR(s.created_at) as year, q.peo_id, sa.score, sa.answer_text, q.question_type, q.question_config
    FROM survey_answers sa
    JOIN survey_questions q ON sa.question_id = q.question_id
    JOIN survey_responses sr ON sa.response_id = sr.response_id
    JOIN surveys s ON sr.survey_id = s.survey_id
")->fetchAll(PDO::FETCH_ASSOC);

$peoTrend = [];
$yearsList = array_unique(array_column($trendDataRaw, 'year'));
sort($yearsList);

foreach ($trendDataRaw as $row) {
    $y = $row['year'];
    $p = $row['peo_id'];
    $s = null;
    if ($row['question_type'] == 'rating' || $row['question_type'] == 'scale') $s = (int)$row['score'];
    else $s = getScore($row['answer_text'], $row['question_config']);
    
    if ($s !== null && $s > 0) $peoTrend[$p][$y][] = $s;
}

foreach ($peoTrend as $p => $years) {
    foreach ($years as $y => $scores) {
        $peoTrend[$p][$y] = round(array_sum($scores) / (count($scores) * 5) * 100);
    }
}

/* =========================
   RESPONSE RATE
========================= */
$stmtRate = $pdo->prepare("
    SELECT a.batch_year, COUNT(DISTINCT a.id) total, COUNT(DISTINCT sr.alumni_id) responded
    FROM alumni_students a
    LEFT JOIN survey_responses sr ON a.id = sr.alumni_id
    GROUP BY a.batch_year
    ORDER BY a.batch_year DESC
");
$stmtRate->execute();
$responseRates = $stmtRate->fetchAll(PDO::FETCH_ASSOC);

include "layout/header.php";
?>

<?php include "layout/sidebar.php"; ?>

<style>
:root {
    --primary: #4f46e5;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --bg-light: #f8fafc;
    --card-bg: #ffffff;
    --text-main: #1e293b;
    --text-muted: #64748b;
    --border: #e2e8f0;
}

.main-content {
    margin-left: 110px;
    padding: 2.5rem;
    box-sizing: border-box;
    min-height: 100vh;
    background-color: var(--bg-light);
}

/* =========================
   HEADER & ACTIONS
========================= */
.reports-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 2.5rem;
}

.reports-header h2 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-main);
    margin: 0;
    letter-spacing: -0.025em;
}

.reports-header p {
    color: var(--text-muted);
    margin: 0.5rem 0 0 0;
    font-size: 1.1rem;
}

.btn-pdf {
    background: var(--primary);
    color: white;
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.625rem;
    transition: all 0.2s ease;
    box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2);
}

.btn-pdf:hover {
    background: #4338ca;
    transform: translateY(-1px);
    box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.3);
}

/* =========================
   FILTERS
========================= */
.filter-section {
    background: var(--card-bg);
    padding: 1.75rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    display: flex;
    gap: 2rem;
    align-items: flex-end;
    margin-bottom: 2.5rem;
    border: 1px solid var(--border);
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    flex: 1;
}

.filter-group label {
    font-size: 0.875rem;
    font-weight: 700;
    color: var(--text-main);
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.filter-group select {
    padding: 0.75rem;
    border: 1px solid var(--border);
    border-radius: 10px;
    font-size: 0.95rem;
    color: var(--text-main);
    background: #fff;
    cursor: pointer;
    transition: border-color 0.2s;
}

/* =========================
   KPI CARDS
========================= */
.peo-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 1.25rem;
    margin-bottom: 2.5rem;
}

.peo-stat-card {
    background: var(--card-bg);
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border-left: 5px solid #e2e8f0;
    position: relative;
    transition: transform 0.2s;
    border: 1px solid var(--border);
    border-left-width: 5px;
}

.peo-stat-card.pass { border-left-color: var(--success); }
.peo-stat-card.fail { border-left-color: var(--danger); }
.peo-stat-card.warning { border-left-color: var(--warning); }

.peo-stat-card h4 {
    margin: 0;
    color: var(--text-muted);
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.peo-stat-card .score {
    font-size: 2rem;
    font-weight: 800;
    margin: 0.75rem 0;
    color: var(--text-main);
}

.peo-stat-card .yoy {
    font-size: 0.875rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 0.375rem;
}

.peo-stat-card .yoy.up { color: var(--success); }
.peo-stat-card .yoy.down { color: var(--danger); }

.peo-stat-card .target {
    font-size: 0.8125rem;
    color: var(--text-muted);
    margin-top: 0.75rem;
    font-weight: 500;
}

.peo-stat-card .status-icon {
    position: absolute;
    top: 1.5rem;
    right: 1.5rem;
    font-size: 1.25rem;
}

/* =========================
   CHARTS & GRIDS
========================= */
.chart-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 2rem;
    margin-bottom: 2rem;
}

.chart-card {
    background: var(--card-bg);
    padding: 1.75rem;
    border-radius: 16px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border);
}

.chart-card h4 {
    margin: 0 0 1.75rem 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-main);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.chart-container {
    height: 320px;
    position: relative;
}

/* =========================
   TABLES
========================= */
.stats-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    margin-top: 1rem;
}

.stats-table th {
    text-align: left;
    padding: 1rem;
    background: #f8fafc;
    border-bottom: 2px solid var(--border);
    color: var(--text-muted);
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stats-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border);
    font-size: 0.9375rem;
    font-weight: 500;
}

/* =========================
   PRINTING
========================= */
@media print {
    .sidebar, .filter-section, .btn-pdf, .reports-header p { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; width: 100% !important; background: white; }
    .peo-grid { grid-template-columns: repeat(5, 1fr); gap: 10px; }
    .chart-grid { grid-template-columns: 1fr; }
}
</style>


<div class="main-content">
    <div class="reports-header">
        <div>
            <h2>Reports & Analytics</h2>
            <p>PEO achievement analysis and performance trends</p>
        </div>
        <button onclick="window.print()" class="btn-pdf">
            <i class="fa-solid fa-file-pdf"></i> PDF
        </button>
    </div>

    <form method="GET" class="filter-section">
        <div class="filter-group">
            <label>Batch</label>
            <select name="batch" onchange="this.form.submit()">
                <option value="all">All Batches</option>
                <?php foreach ($batches as $b): ?>
                    <option value="<?= $b ?>" <?= $b == $selected_batch ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="filter-group">
            <label>Programme</label>
            <select name="programme" onchange="this.form.submit()">
                <option value="all">All Programmes</option>
                <?php foreach ($programmes as $p): ?>
                    <option value="<?= $p ?>" <?= $p == $selected_programme ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>

    <div class="peo-grid">
        <?php foreach ($peoList as $peo): 
            $score = $currentData['peo'][$peo] ?? 0;
            $prevScore = $prevData['peo'][$peo] ?? 0;
            $diff = $score - $prevScore;
            $status = $score >= 70 ? 'pass' : ($score > 0 ? 'warning' : 'fail');
            $icon = $score >= 70 ? 'fa-circle-check' : ($score > 0 ? 'fa-circle-exclamation' : 'fa-triangle-exclamation');
        ?>
        <div class="peo-stat-card <?= $status ?>">
            <h4><?= $peo ?></h4>
            <div class="score"><?= $score ?>%</div>
            <?php if ($score > 0): ?>
                <div class="yoy <?= $diff >= 0 ? 'up' : 'down' ?>">
                    <i class="fa-solid <?= $diff >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' ?>"></i>
                    <?= ($diff >= 0 ? '+' : '') . $diff ?>% YoY
                </div>
                <div class="target">Target: 70% <?= $score >= 70 ? '✓' : '' ?></div>
                <div class="status-icon <?= $status ?>"><i class="fa-solid <?= $icon ?>"></i></div>
            <?php else: ?>
                <div style="color: var(--danger); font-size: 0.7rem; font-weight: 600;">
                    <i class="fa-solid fa-triangle-exclamation"></i> No data available
                </div>
                <div class="target">Target: 70%</div>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h4><i class="fa-solid fa-chart-line" style="color: var(--primary)"></i> PEO Achievement Trends (5 Years)</h4>
            <div class="chart-container">
                <canvas id="trendChart"></canvas>
            </div>
            <?php if (count($yearsList) < 2): ?>
                <small style="color: var(--warning); margin-top: 1rem; display: block;">
                    <i class="fa-solid fa-circle-info"></i> Limited data only - historical trends unavailable
                </small>
            <?php endif; ?>
        </div>

        <div class="chart-card">
            <h4><i class="fa-solid fa-chart-bar" style="color: var(--success)"></i> Response Rate by Batch</h4>
            <div class="chart-container">
                <canvas id="responseChart"></canvas>
            </div>
        </div>
    </div>

    <div class="chart-grid">
        <div class="chart-card">
            <h4><i class="fa-solid fa-bullseye" style="color: #ef4444"></i> PEO to PLO Achievement Mapping</h4>
            <div class="chart-container">
                <canvas id="ploChart"></canvas>
            </div>
        </div>

        <div class="chart-card">
            <h4><i class="fa-solid fa-table-list" style="color: var(--warning)"></i> Detailed Statistics</h4>
            <table class="stats-table">
                <thead>
                    <tr>
                        <th>PEO</th>
                        <th>AVG</th>
                        <th>MIN</th>
                        <th>MAX</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($currentData['stats'])): ?>
                        <tr><td colspan="4" style="text-align: center; color: var(--text-muted);">No data available for the selected filters</td></tr>
                    <?php else: ?>
                        <?php foreach ($currentData['stats'] as $row): ?>
                        <tr>
                            <td><?= $row['peo_id'] ?></td>
                            <td><?= round(($row['avg']/5)*100) ?>%</td>
                            <td><?= round(($row['min']/5)*100) ?>%</td>
                            <td><?= round(($row['max']/5)*100) ?>%</td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            <?php if (empty($currentData['stats'])): ?>
                <small style="color: var(--warning); margin-top: 1rem; display: block;">
                    <i class="fa-solid fa-triangle-exclamation"></i> PEO data excluded - no data available
                </small>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colors = ['#4f46e5', '#10b981', '#f59e0b', '#ef4444', '#8b5cf6', '#06b6d4', '#ec4899'];

// TREND CHART
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($yearsList) ?>,
        datasets: [
            <?php $i=0; foreach ($peoTrend as $peo => $years): ?>
            {
                label: '<?= $peo ?>',
                data: <?= json_encode(array_values(array_intersect_key($years, array_flip($yearsList)))) ?>,
                borderColor: colors[<?= $i++ ?> % colors.length],
                tension: 0.3,
                borderWidth: 2,
                pointRadius: 4
            },
            <?php endforeach; ?>
            {
                label: 'Target (70%)',
                data: Array(<?= count($yearsList) ?>).fill(70),
                borderColor: '#cbd5e1',
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, usePointStyle: true } } },
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});

// RESPONSE CHART
new Chart(document.getElementById('responseChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($responseRates, 'batch_year')) ?>,
        datasets: [{
            label: 'Response Rate %',
            data: <?= json_encode(array_map(function($r){ return $r['total'] > 0 ? round(($r['responded']/$r['total'])*100) : 0; }, $responseRates)) ?>,
            backgroundColor: 'rgba(79, 70, 229, 0.8)',
            borderRadius: 6
        },
        {
            label: 'Low Confidence (10%)',
            type: 'line',
            data: Array(<?= count($responseRates) ?>).fill(10),
            borderColor: '#ef4444',
            borderDash: [5, 5],
            pointRadius: 0,
            fill: false
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } },
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});

// PLO CHART
new Chart(document.getElementById('ploChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode($ploList) ?>,
        datasets: [{
            label: 'Achievement %',
            data: <?= json_encode(array_map(function($plo) use ($currentData) { return $currentData['plo'][$plo] ?? 0; }, $ploList)) ?>,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderRadius: 6
        },
        {
            label: 'Target (70%)',
            type: 'line',
            data: Array(<?= count($ploList) ?>).fill(70),
            borderColor: '#94a3b8',
            borderDash: [5, 5],
            pointRadius: 0,
            fill: false
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { y: { beginAtZero: true, max: 100 } }
    }
});
</script>

<?php include "layout/footer.php"; ?>