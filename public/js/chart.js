const ctx = document.getElementById('salesChart').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: {
        labels: window.salesChartLabels,
        datasets: [{
            label: 'Sales',
            data: window.salesChartValues,
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79, 70, 229, 0.2)',
            borderWidth: 3,
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        plugins: {
            legend: {
                display: false   // 🔥 Jelmagyarázat kikapcsolva
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
