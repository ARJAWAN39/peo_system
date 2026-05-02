<?php
// This file is included from reports.php after PHP logic
// Variables available: $selected_year, $selected_batch, $selected_programme,
// $years, $batches, $programmes, $peoList, $ploList, $peoToPlo,
// $currentData, $prevData, $peoTrend, $yearsList, $responseRates

// PEO descriptions map
$peoDescriptions = [];
$descStmt = $pdo->query("SELECT DISTINCT peo_code, peo_description FROM peo_plo_mapping WHERE peo_code IS NOT NULL AND peo_description IS NOT NULL");
while ($r = $descStmt->fetch(PDO::FETCH_ASSOC)) {
    $peoDescriptions[$r['peo_code']] = $r['peo_description'];
}

// PEO short names
$peoShortNames = [
    'PEO 1' => 'Professional Excellence',
    'PEO 2' => 'Technical Competency', 
    'PEO 3' => 'Effective Communication',
    'PEO 4' => 'Ethical Leadership',
    'PEO 5' => 'Continuous Learning'
];

// Programme label
$progLabel = $selected_programme === 'all' ? 'All Programmes (' . implode(' & ', $programmes) . ')' : $selected_programme;
$yearLabel = $selected_batch === 'all' ? 'All Years (' . min($batches) . '-' . max($batches) . ')' : $selected_batch;

// Survey questions by PEO
$questionsByPeo = [];
$qStmt = $pdo->query("SELECT peo_id, question_text FROM survey_questions WHERE peo_id IS NOT NULL");
while ($r = $qStmt->fetch(PDO::FETCH_ASSOC)) {
    $questionsByPeo[$r['peo_id']][] = $r['question_text'];
}

// Batch-level data per PEO
$batchPeoData = [];
foreach ($batches as $b) {
    $bd = calculateAchievement($pdo, $selected_year, $b, $selected_programme, $peoToPlo);
    foreach ($bd['peo'] as $p => $s) {
        $batchPeoData[$p][$b] = $s;
    }
}

include "layout/header.php";
?>
<link rel="stylesheet" href="assets/css/reports.css">
<?php include "layout/sidebar.php"; ?>

<div class="reports-wrap">
    <!-- HEADER CARD -->
    <div class="report-header-card">
        <div class="report-header-top">
            <div>
                <h1>PEO Achievement Analysis Report</h1>
                <div class="report-meta">
                    <?= $progLabel ?><br>
                    Academic Year: <?= $yearLabel ?><br>
                    Generated: <?= date('F j, Y') ?>
                </div>
            </div>
            <button onclick="window.print()" class="btn-generate-pdf">
                <i class="fa-solid fa-file-pdf"></i> Generate PDF Report
            </button>
        </div>
        <hr class="report-divider">
        <form method="GET" class="report-filters">
            <span class="filter-label">View Data For:</span>
            <span class="filter-sublabel">Programme:</span>
            <select name="programme" onchange="this.form.submit()">
                <option value="all">All Programmes</option>
                <?php foreach ($programmes as $p): ?>
                    <option value="<?= $p ?>" <?= $p == $selected_programme ? 'selected' : '' ?>><?= $p ?></option>
                <?php endforeach; ?>
            </select>
            <span class="filter-sublabel">Year:</span>
            <select name="batch" onchange="this.form.submit()">
                <option value="all">All Years (<?= !empty($batches) ? min($batches).'-'.max($batches) : '' ?>)</option>
                <?php foreach ($batches as $b): ?>
                    <option value="<?= $b ?>" <?= $b == $selected_batch ? 'selected' : '' ?>><?= $b ?></option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <!-- OVERALL PERFORMANCE DASHBOARD -->
    <div class="section-card">
        <h2 class="section-title">Overall Performance Dashboard</h2>
        <div class="peo-summary-grid">
            <?php foreach ($peoList as $peo):
                $score = $currentData['peo'][$peo] ?? 0;
                $prevScore = $prevData['peo'][$peo] ?? 0;
                $diff = $score - $prevScore;
                $colorClass = $score >= 70 ? 'green' : ($score >= 50 ? 'amber' : 'red');
                $target = $peo === 'PEO 2' ? 75 : 70;
                $confidence = $score >= 70 ? 'High' : ($score >= 50 ? 'Medium' : 'Low');
                $confClass = strtolower($confidence);
                $shortName = $peoShortNames[$peo] ?? '';
            ?>
            <div class="peo-summary-card">
                <div class="peo-card-header">
                    <h3><?= $peo ?></h3>
                    <span class="trend-icon <?= $diff < 0 ? 'down' : '' ?>">
                        <i class="fa-solid <?= $diff >= 0 ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' ?>"></i>
                    </span>
                </div>
                <div class="peo-card-desc"><?= $shortName ?></div>
                <div class="peo-metrics">
                    <div class="peo-metric-row">
                        <span class="metric-label">Achievement:</span>
                        <span class="metric-value achievement <?= $colorClass ?>"><?= $score ?>%</span>
                    </div>
                    <div class="peo-metric-row">
                        <span class="metric-label">Target:</span>
                        <span class="metric-value"><?= $target ?>%</span>
                    </div>
                    <div class="peo-metric-row">
                        <span class="metric-label">Trend:</span>
                        <span class="metric-value <?= $diff >= 0 ? 'trend-up' : 'trend-down' ?>"><?= ($diff >= 0 ? '+' : '') . $diff ?>%</span>
                    </div>
                    <div class="peo-metric-row">
                        <span class="metric-label">Confidence:</span>
                        <span class="confidence-badge <?= $confClass ?>"><?= $confidence ?></span>
                    </div>
                </div>
                <div class="peo-progress-bar">
                    <div class="peo-progress-fill <?= $colorClass ?>" style="width: <?= min($score, 100) ?>%"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CHARTS ROW -->
    <div class="chart-row">
        <div class="chart-panel">
            <h4><i class="fa-solid fa-chart-line" style="color:#2563eb"></i> 5-Year Performance Trend</h4>
            <div class="chart-box-inner"><canvas id="trendChart"></canvas></div>
        </div>
        <div class="chart-panel">
            <h4><i class="fa-solid fa-chart-bar" style="color:#22c55e"></i> Batch Response Rates</h4>
            <div class="chart-box-inner"><canvas id="responseChart"></canvas></div>
        </div>
    </div>

    <!-- DETAILED PEO PERFORMANCE -->
    <div class="section-card" style="background:transparent;border:none;box-shadow:none;padding:0;">
        <h2 class="section-title" style="margin-bottom:1rem;">Detailed PEO Performance Analysis</h2>
    </div>

    <?php foreach ($peoList as $peo):
        $score = $currentData['peo'][$peo] ?? 0;
        $statusClass = $score >= 70 ? 'pass' : ($score >= 50 ? 'warning' : 'fail');
        $statusText = $score >= 70 ? 'Target Met' : ($score >= 50 ? 'Near Target' : 'Below Target');
        $desc = $peoDescriptions[$peo] ?? '';
        $mappedPlos = $peoToPlo[$peo] ?? [];
        $questions = $questionsByPeo[$peo] ?? [];
        $batchScores = $batchPeoData[$peo] ?? [];
    ?>
    <div class="peo-detail-card">
        <div class="peo-detail-header">
            <h3><?= $peo ?> — <?= $peoShortNames[$peo] ?? '' ?></h3>
            <span class="detail-badge <?= $statusClass ?>"><?= $score ?>% — <?= $statusText ?></span>
        </div>
        <div class="peo-detail-desc"><?= $desc ?></div>
        <div class="peo-detail-grid">
            <div class="peo-detail-section">
                <h5>Mapped PLOs</h5>
                <?php if (!empty($mappedPlos)): ?>
                    <?php foreach (array_unique($mappedPlos) as $plo): ?>
                        <span class="plo-tag"><?= $plo ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="color:#6b7a99;font-size:0.85rem;">No PLOs mapped</span>
                <?php endif; ?>
            </div>
            <div class="peo-detail-section">
                <h5>Survey Questions</h5>
                <?php if (!empty($questions)): ?>
                    <?php foreach (array_slice($questions, 0, 3) as $q): ?>
                        <div class="question-item"><?= htmlspecialchars($q) ?></div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span style="color:#6b7a99;font-size:0.85rem;">No questions</span>
                <?php endif; ?>
            </div>
            <div class="peo-detail-section">
                <h5>Performance by Batch</h5>
                <div class="peo-detail-chart"><canvas id="batchChart_<?= str_replace(' ', '', $peo) ?>"></canvas></div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

    <!-- DATA QUALITY TABLE -->
    <div class="chart-row">
        <div class="chart-panel">
            <h4><i class="fa-solid fa-bullseye" style="color:#ef4444"></i> PEO to PLO Achievement</h4>
            <div class="chart-box-inner"><canvas id="ploChart"></canvas></div>
        </div>
        <div class="chart-panel">
            <h4><i class="fa-solid fa-table-list" style="color:#f59e0b"></i> Data Quality & Confidence</h4>
            <table class="stats-table-modern">
                <thead><tr><th>PEO</th><th>Achievement</th><th>Avg Score</th><th>Min</th><th>Max</th><th>Status</th></tr></thead>
                <tbody>
                <?php if (empty($currentData['stats'])): ?>
                    <tr><td colspan="6" style="text-align:center;color:#6b7a99;">No data available</td></tr>
                <?php else: ?>
                    <?php foreach ($currentData['stats'] as $row):
                        $pct = round(($row['avg']/5)*100);
                        $dotClass = $pct >= 70 ? 'green' : ($pct >= 50 ? 'amber' : 'red');
                    ?>
                    <tr>
                        <td><strong><?= $row['peo_id'] ?></strong></td>
                        <td><?= $currentData['peo'][$row['peo_id']] ?? 0 ?>%</td>
                        <td><?= $pct ?>%</td>
                        <td><?= round(($row['min']/5)*100) ?>%</td>
                        <td><?= round(($row['max']/5)*100) ?>%</td>
                        <td><span class="status-dot <?= $dotClass ?>"></span><?= $pct >= 70 ? 'Pass' : 'Below' ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const colors = ['#2563eb','#22c55e','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#ec4899'];
const chartFont = { family: 'Inter, sans-serif' };

Chart.defaults.font.family = 'Inter, sans-serif';
Chart.defaults.font.size = 12;

// TREND CHART
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: <?= json_encode($yearsList) ?>,
        datasets: [
            <?php $i=0; foreach ($peoTrend as $peo => $yrs): ?>
            {
                label: '<?= $peo ?>',
                data: <?= json_encode(array_values(array_map(function($y) use ($yrs) { return $yrs[$y] ?? null; }, $yearsList))) ?>,
                borderColor: colors[<?= $i++ ?> % colors.length],
                backgroundColor: 'transparent',
                tension: 0.35,
                borderWidth: 2.5,
                pointRadius: 4,
                pointBackgroundColor: '#fff',
                pointBorderWidth: 2
            },
            <?php endforeach; ?>
            {
                label: 'Target (70%)',
                data: Array(<?= count($yearsList) ?>).fill(70),
                borderColor: '#cbd5e1',
                borderDash: [6, 4],
                pointRadius: 0,
                borderWidth: 1.5,
                fill: false
            }
        ]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 16 } } },
        scales: { y: { beginAtZero: true, max: 100, grid: { color: '#f0f2f5' } }, x: { grid: { display: false } } }
    }
});

// RESPONSE CHART
new Chart(document.getElementById('responseChart'), {
    type: 'bar',
    data: {
        labels: <?= json_encode(array_column($responseRates, 'batch_year')) ?>,
        datasets: [{
            label: 'Responded',
            data: <?= json_encode(array_map(function($r){ return (int)$r['responded']; }, $responseRates)) ?>,
            backgroundColor: '#2563eb',
            borderRadius: 6,
            barPercentage: 0.5
        },{
            label: 'Total',
            data: <?= json_encode(array_map(function($r){ return (int)$r['total']; }, $responseRates)) ?>,
            backgroundColor: '#e0e7ff',
            borderRadius: 6,
            barPercentage: 0.5
        }]
    },
    options: {
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 16 } } },
        scales: { y: { beginAtZero: true, grid: { color: '#f0f2f5' } }, x: { grid: { display: false } } }
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
            backgroundColor: '#818cf8',
            borderRadius: 6,
            barPercentage: 0.6
        },{
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
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, usePointStyle: true, padding: 16 } } },
        scales: { y: { beginAtZero: true, max: 100, grid: { color: '#f0f2f5' } }, x: { grid: { display: false } } }
    }
});

// BATCH CHARTS per PEO
<?php foreach ($peoList as $peo):
    $bScores = $batchPeoData[$peo] ?? [];
    $peoId = str_replace(' ', '', $peo);
?>
if (document.getElementById('batchChart_<?= $peoId ?>')) {
    new Chart(document.getElementById('batchChart_<?= $peoId ?>'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_keys($bScores)) ?>,
            datasets: [{
                data: <?= json_encode(array_values($bScores)) ?>,
                backgroundColor: '#2563eb',
                borderRadius: 4,
                barPercentage: 0.6
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, max: 100, grid: { color: '#f0f2f5' } }, x: { grid: { display: false } } }
        }
    });
}
<?php endforeach; ?>
</script>

<?php include "layout/footer.php"; ?>
