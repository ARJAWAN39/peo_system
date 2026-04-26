// -------- PIE CHART (Survey Completion) -------- //
var ctx1 = document.getElementById('surveyPie').getContext('2d');

new Chart(ctx1, {
    type: 'pie',
    data: {
        labels: ['Completed', 'Pending', 'Not Started'],
        datasets: [{
            data: [45, 20, 35],
            backgroundColor: ['#4ade80', '#60a5fa', '#f87171'],
        }]
    },
    options: {
        responsive: true
    }
});

// -------- BAR CHART (PEO Achievement) -------- //
var ctx2 = document.getElementById('peoBar').getContext('2d');

new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: ['PEO1', 'PEO2', 'PEO3', 'PEO4', 'PEO5'],
        datasets: [{
            label: 'Achievement (%)',
            data: [70, 85, 65, 78, 90],
        }]
    },
    options: {
        scales: {
            y: {
                beginAtZero: true,
                max: 100
            }
        }
    }
});
