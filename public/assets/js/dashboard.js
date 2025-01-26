document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('projectsChart');
    if (!ctx) return;

    // Destruir qualquer instância existente do gráfico
    const existingChart = Chart.getChart(ctx);
    if (existingChart) {
        existingChart.destroy();
    }
    
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: chartData.labels,
            datasets: [{
                data: chartData.data,
                backgroundColor: [
                    '#4F46E5', // Indigo
                    '#10B981', // Green
                    '#F59E0B', // Yellow
                    '#EF4444', // Red
                    '#6366F1', // Purple
                    '#3B82F6'  // Blue
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        color: document.documentElement.classList.contains('dark') ? '#fff' : '#000',
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            let value = context.raw || 0;
                            return label + 'R$ ' + parseFloat(value).toLocaleString('pt-BR', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
                        }
                    }
                }
            },
            cutout: '70%'
        }
    });
});
