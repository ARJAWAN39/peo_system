<?php
session_start();
require_once "config.php";

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

include "reports_template.php";
?>
