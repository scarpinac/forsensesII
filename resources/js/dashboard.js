import Chart from 'chart.js/auto';

// Tornar Chart disponível globalmente
window.Chart = Chart;

window.jQuery = window.$ = jQuery;

$(function() {
    // Verificar se os dados estão disponíveis
    if (!window.dashboardData) {
        console.error('Dados do dashboard não encontrados');
        return;
    }

    // Gráfico de Receita Mensal (Orçamentos vs Pedidos)
    const revenueCtx = document.getElementById('revenueChart');
    if (revenueCtx) {
        const revenueData = window.dashboardData.monthly_revenue;
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.labels,
                datasets: [
                    {
                        label: 'Orçamentos',
                        data: revenueData.quotes,
                        borderColor: '#ffc107',
                        backgroundColor: 'rgba(255, 193, 7, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Pedidos',
                        data: revenueData.orders,
                        borderColor: '#dc3545',
                        backgroundColor: 'rgba(220, 53, 69, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += 'R$ ' + context.parsed.y.toLocaleString('pt-BR');
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'R$ ' + value.toLocaleString('pt-BR');
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Categorias de Produtos
    const categoryCtx = document.getElementById('categoryChart');
    if (categoryCtx) {
        const categoryData = window.dashboardData.product_categories;
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.labels,
                datasets: [{
                    data: categoryData.data,
                    backgroundColor: [
                        '#007bff',
                        '#28a745',
                        '#ffc107',
                        '#dc3545',
                        '#6c757d',
                        '#17a2b8'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + '%';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Segmentos de Clientes
    const customerSegmentCtx = document.getElementById('customerSegmentChart');
    if (customerSegmentCtx) {
        const segmentData = window.dashboardData.customer_segments;
        new Chart(customerSegmentCtx, {
            type: 'pie',
            data: {
                labels: segmentData.labels,
                datasets: [{
                    data: segmentData.data,
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#28a745',
                        '#6c757d'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                label += context.parsed + '%';
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Gráfico de Status de Pedidos
    const orderStatusCtx = document.getElementById('orderStatusChart');
    if (orderStatusCtx) {
        const statusData = window.dashboardData.order_status;
        new Chart(orderStatusCtx, {
            type: 'bar',
            data: {
                labels: statusData.labels,
                datasets: [{
                    label: 'Quantidade',
                    data: statusData.data,
                    backgroundColor: [
                        '#ffc107',
                        '#17a2b8',
                        '#28a745',
                        '#dc3545'
                    ],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 50
                        }
                    }
                }
            }
        });
    }

    // Adicionar interatividade aos cards
    $('.small-box').on('click', function(e) {
        if (!$(e.target).closest('a').length) {
            const cardType = $(this).find('p').text().trim();
            console.log(`Clique no card: ${cardType}`);
            // Aqui você pode adicionar lógica para navegar para páginas detalhadas
        }
    });

    // Animação dos números nos cards
    $('.small-box h3, .info-box-number').each(function() {
        const $this = $(this);
        const countTo = parseInt($this.text().replace(/[^0-9]/g, ''));
        if (!isNaN(countTo)) {
            $this.text('0');
            $this.prop('Counter', 0).animate({
                Counter: countTo
            }, {
                duration: 2000,
                easing: 'swing',
                step: function(now) {
                    $this.text(Math.ceil(now).toLocaleString('pt-BR'));
                }
            });
        }
    });
});
