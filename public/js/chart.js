document.addEventListener("DOMContentLoaded", () => {
    const el = document.getElementById("chartData");
    if (!el) return;

    const labels = JSON.parse(el.dataset.labels);
    const values = JSON.parse(el.dataset.values);

    const ctx = document.getElementById("salesChart");
    if (!ctx) return;

    new Chart(ctx, {
        type: 'line',
        data: {
            labels,
            datasets: [{
                label: 'Sales',
                data: values,
                borderColor: '#4f46e5',
                backgroundColor: 'rgba(79, 70, 229, 0.2)',
                borderWidth: 3,
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
});
