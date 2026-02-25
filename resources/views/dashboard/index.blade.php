@extends('layouts.adminlte-with-language')
@push('css')
    @vite(['resources/scss/custom.scss'])
@endpush
@section('content')
<div class="container-fluid">
    <!-- Cards de Estatísticas -->
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ number_format($stats['pedidos']['total'], 0, ',', '.') }}</h3>
                    <p>Total de Pedidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>R$ {{ number_format($stats['pedidos']['valor_total'], 2, ',', '.') }}</h3>
                    <p>Valor Total Pedidos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ number_format($stats['orcamentos']['total'], 0, ',', '.') }}</h3>
                    <p>Total de Orçamentos</p>
                </div>
                <div class="icon">
                    <i class="fas fa-file-invoice"></i>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-danger">
                <div class="inner">
                    <h3>{{ $stats['conversao']['taxa'] }}%</h3>
                    <p>Taxa de Conversão</p>
                </div>
                <div class="icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row">
        <!-- Pedidos Diários -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Pedidos Diários (Últimos 30 dias)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="atualizarGrafico('pedidos_diarios')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="pedidosDiariosChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Orçamentos Diários -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orçamentos Diários (Últimos 30 dias)</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="atualizarGrafico('orcamentos_diarios')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="orcamentosDiariosChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Valor Mensal -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Valor Mensal de Pedidos</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="atualizarGrafico('valor_mensal')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="valorMensalChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>

        <!-- Taxa de Conversão Mensal -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Taxa de Conversão Mensal</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" onclick="atualizarGrafico('conversao_mensal')">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="conversaoMensalChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Dados iniciais dos gráficos
const dadosIniciais = @json($graficos);

// Configuração padrão dos gráficos
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: true,
            position: 'top',
        }
    },
    scales: {
        y: {
            beginAtZero: true
        }
    }
};

// Gráfico de Pedidos Diários
const pedidosDiariosCtx = document.getElementById('pedidosDiariosChart').getContext('2d');
const pedidosDiariosChart = new Chart(pedidosDiariosCtx, {
    type: 'line',
    data: {
        labels: dadosIniciais.pedidos_diarios.map(item => item.data),
        datasets: [{
            label: 'Pedidos',
            data: dadosIniciais.pedidos_diarios.map(item => item.quantidade),
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4
        }]
    },
    options: chartDefaults
});

// Gráfico de Orçamentos Diários
const orcamentosDiariosCtx = document.getElementById('orcamentosDiariosChart').getContext('2d');
const orcamentosDiariosChart = new Chart(orcamentosDiariosCtx, {
    type: 'line',
    data: {
        labels: dadosIniciais.orcamentos_diarios.map(item => item.data),
        datasets: [{
            label: 'Orçamentos',
            data: dadosIniciais.orcamentos_diarios.map(item => item.quantidade),
            borderColor: 'rgb(34, 197, 94)',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4
        }]
    },
    options: chartDefaults
});

// Gráfico de Valor Mensal
const valorMensalCtx = document.getElementById('valorMensalChart').getContext('2d');
const valorMensalChart = new Chart(valorMensalCtx, {
    type: 'bar',
    data: {
        labels: dadosIniciais.valor_mensal.map(item => item.periodo),
        datasets: [{
            label: 'Valor Total (R$)',
            data: dadosIniciais.valor_mensal.map(item => item.total),
            backgroundColor: 'rgba(251, 146, 60, 0.8)',
            borderColor: 'rgb(251, 146, 60)',
            borderWidth: 1
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            ...chartDefaults.scales,
            y: {
                ...chartDefaults.scales.y,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});

// Gráfico de Taxa de Conversão Mensal
const conversaoMensalCtx = document.getElementById('conversaoMensalChart').getContext('2d');
const conversaoMensalChart = new Chart(conversaoMensalCtx, {
    type: 'line',
    data: {
        labels: dadosIniciais.conversao_mensal.map(item => item.periodo),
        datasets: [{
            label: 'Taxa de Conversão (%)',
            data: dadosIniciais.conversao_mensal.map(item => item.taxa),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        ...chartDefaults,
        scales: {
            ...chartDefaults.scales,
            y: {
                ...chartDefaults.scales.y,
                ticks: {
                    callback: function(value) {
                        return value + '%';
                    }
                }
            }
        }
    }
});

// Função para atualizar gráficos
function atualizarGrafico(tipo) {
    fetch(`/dashboard/dados-graficos?tipo=${tipo}`)
        .then(response => response.json())
        .then(data => {
            switch (tipo) {
                case 'pedidos_diarios':
                    pedidosDiariosChart.data.labels = data.map(item => item.data);
                    pedidosDiariosChart.data.datasets[0].data = data.map(item => item.quantidade);
                    pedidosDiariosChart.update();
                    break;
                case 'orcamentos_diarios':
                    orcamentosDiariosChart.data.labels = data.map(item => item.data);
                    orcamentosDiariosChart.data.datasets[0].data = data.map(item => item.quantidade);
                    orcamentosDiariosChart.update();
                    break;
                case 'valor_mensal':
                    valorMensalChart.data.labels = data.map(item => item.periodo);
                    valorMensalChart.data.datasets[0].data = data.map(item => item.total);
                    valorMensalChart.update();
                    break;
                case 'conversao_mensal':
                    conversaoMensalChart.data.labels = data.map(item => item.periodo);
                    conversaoMensalChart.data.datasets[0].data = data.map(item => item.taxa);
                    conversaoMensalChart.update();
                    break;
            }
        })
        .catch(error => {
            console.error('Erro ao atualizar gráfico:', error);
            toastr.error('Erro ao atualizar gráfico');
        });
}

// Auto-atualizar a cada 5 minutos
setInterval(() => {
    atualizarGrafico('pedidos_diarios');
    atualizarGrafico('orcamentos_diarios');
    atualizarGrafico('valor_mensal');
    atualizarGrafico('conversao_mensal');
}, 300000);
</script>
@endsection
