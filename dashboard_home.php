<?php
require_once "db.php";

/* =============================
   DASHBOARD COUNTS (REAL)
============================= */

$totalAlumni = $pdo->query("SELECT COUNT(*) FROM alumni_students")->fetchColumn();
$activeSurveys = $pdo->query("SELECT COUNT(*) FROM surveys")->fetchColumn();
$totalResponses = $pdo->query("SELECT COUNT(*) FROM survey_assignments")->fetchColumn();

$avgAchievement = 78;

/* =============================
   RECENT ACTIVITY
============================= */
$activities = [];

$surveyActivities = $pdo->query("
    SELECT survey_title, created_at
    FROM surveys
    ORDER BY created_at DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($surveyActivities as $s) {
    $activities[] = ['activity'=>"Survey created: ".$s['survey_title'],'time'=>$s['created_at']];
}

$alumniActivities = $pdo->query("
    SELECT created_at
    FROM alumni_students
    ORDER BY created_at DESC
    LIMIT 3
")->fetchAll(PDO::FETCH_ASSOC);

foreach ($alumniActivities as $a) {
    $activities[] = ['activity'=>"New alumni account registered",'time'=>$a['created_at']];
}

usort($activities, fn($a,$b)=>strtotime($b['time'])-strtotime($a['time']));
?>

<style>
/* 🔑 MASTER WIDTH CONTROL */
.dashboard-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 25px 25px;
}

/* ===== TOP SUMMARY ===== */
.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 35px;
}

.card {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 14px;
    padding: 22px;
}

.card h4 {
    font-size: 13px;
    color: #374151;
    margin: 0;
}

.card p {
    font-size: 30px;
    font-weight: 700;
    margin-top: 12px;
}

/* ===== CHART ROW (PERFECT ALIGN) ===== */
.charts {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 35px;
}

.chart-box {
    background: #fff;
    border: 1.5px solid #e5e7eb;
    border-radius: 14px;
    padding: 18px;
    height: 420px;
    display: flex;
    flex-direction: column;
}

.chart-box h4 {
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 12px;
}

.chart-wrapper {
    flex: 1;
}

.chart-wrapper canvas {
    width: 100% !important;
    height: 100% !important;
}

/* ===== ACTIVITY ===== */
.activity-box {
    background: #fff;
    border: 1px solid #e5e7eb;
    border-radius: 16px;
    padding: 22px;
}

.activity-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.activity-list li {
    display: flex;
    justify-content: space-between;
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
}

.activity-list li:last-child {
    border-bottom: none;
}

.activity-time {
    font-size: 12px;
    color: #6b7280;
}
</style>

<div class="dashboard-container">

<h3>Overview of PEO Achievement Analysis</h3>

<div class="dashboard-cards">
    <div class="card"><h4>TOTAL ALUMNI</h4><p><?= $totalAlumni ?></p></div>
    <div class="card"><h4>ACTIVE SURVEYS</h4><p><?= $activeSurveys ?></p></div>
    <div class="card"><h4>RESPONSES</h4><p><?= $totalResponses ?></p></div>
    <div class="card"><h4>AVG ACHIEVEMENT</h4><p><?= $avgAchievement ?>%</p></div>
</div>

<div class="charts">
    <div class="chart-box">
        <h4>Survey Status</h4>
        <div class="chart-wrapper"><canvas id="surveyStatusChart"></canvas></div>
    </div>

    <div class="chart-box">
        <h4>PEO Achievement Summary</h4>
        <div class="chart-wrapper"><canvas id="peoSummaryChart"></canvas></div>
    </div>
</div>

<div class="activity-box">
    <h4>Recent Activity</h4>
    <ul class="activity-list">
        <?php foreach ($activities as $act): ?>
        <li>
            <span><?= htmlspecialchars($act['activity']) ?></span>
            <span class="activity-time"><?= date("d M Y", strtotime($act['time'])) ?></span>
        </li>
        <?php endforeach; ?>
    </ul>
</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
new Chart(surveyStatusChart,{
    type:'doughnut',
    data:{
        labels:['Completed','Pending','Overdue'],
        datasets:[{
            data:[60,25,15],
            backgroundColor:['#22c55e','#facc15','#ef4444']
        }]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        cutout:'68%',
        plugins:{
            legend:{
                position:'bottom',
                align:'center',
                labels:{
                    boxWidth:18,
                    padding:15
                }
            }
        }
    }
});

new Chart(peoSummaryChart,{
    type:'bar',
    data:{labels:['PEO 1','PEO 2','PEO 3','PEO 4','PEO 5'],datasets:[{data:[78,85,72,90,68],backgroundColor:'#2563eb'}]},
    options:{responsive:true,maintainAspectRatio:false,scales:{y:{beginAtZero:true,max:100}},plugins:{legend:{display:false}}}
});
</script>
