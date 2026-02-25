@php
    $dashboardId = $dashboardId ?? 'default';
    $panelId = $panelId ?? '1';
    $title = $title ?? 'Gráfico';
    $height = $height ?? '300';
    $from = $from ?? 'now-7d';
    $to = $to ?? 'now';
@endphp

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">{{ $title }}</h5>
        <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-primary" onclick="refreshGraphQLChart('{{ $panelId }}')">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="http://localhost:3000/d/{{ $dashboardId }}" target="_blank" class="btn btn-outline-secondary">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    </div>
    <div class="card-body">
        <div id="chart-{{ $panelId }}" style="height: {{ $height }}px;">
            <canvas id="canvas-{{ $panelId }}"></canvas>
        </div>
        <div id="loading-{{ $panelId }}" class="text-center p-4">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Carregando...</span>
            </div>
        </div>
        <div id="error-{{ $panelId }}" class="alert alert-danger" style="display: none;">
            Erro ao carregar dados do gráfico.
        </div>
    </div>
</div>

<script>
// Carregar Chart.js (se não estiver carregado)
if (typeof Chart === 'undefined') {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    document.head.appendChild(script);
}

function refreshGraphQLChart(panelId) {
    loadGraphQLChart(panelId);
}

async function loadGraphQLChart(panelId) {
    const loading = document.getElementById('loading-' + panelId);
    const error = document.getElementById('error-' + panelId);
    const canvas = document.getElementById('canvas-' + panelId);
    
    loading.style.display = 'block';
    error.style.display = 'none';
    
    try {
        const query = `
            {
                panelData(
                    dashboardId: "{{ $dashboardId }}",
                    panelId: {{ $panelId }},
                    from: "{{ $from }}",
                    to: "{{ $to }}"
                ) {
                    timestamp
                    value
                    metric
                }
            }
        `;
        
        const response = await fetch('/graphql', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ query })
        });
        
        const result = await response.json();
        
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }
        
        const data = result.data.panelData;
        renderChart(panelId, data);
        
    } catch (err) {
        console.error('Error loading chart:', err);
        error.style.display = 'block';
        error.textContent = 'Erro: ' + err.message;
    } finally {
        loading.style.display = 'none';
    }
}

function renderChart(panelId, data) {
    const canvas = document.getElementById('canvas-' + panelId);
    const ctx = canvas.getContext('2d');
    
    // Destruir chart anterior se existir
    if (window.charts && window.charts[panelId]) {
        window.charts[panelId].destroy();
    }
    
    // Preparar dados
    const labels = data.map(d => new Date(d.timestamp * 1000).toLocaleDateString());
    const values = data.map(d => d.value);
    
    // Criar novo chart
    window.charts = window.charts || {};
    window.charts[panelId] = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: data[0]?.metric || 'Dados',
                data: values,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Carregar gráfico quando a página carregar
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(() => {
        loadGraphQLChart('{{ $panelId }}');
    }, 100);
});
</script>
