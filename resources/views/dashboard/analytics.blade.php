@extends('layouts.adminlte-with-language')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Analytics Dashboard</h3>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-primary" onclick="changeTimeRange('now-24h')">24h</button>
            <button type="button" class="btn btn-outline-primary active" onclick="changeTimeRange('now-7d')">7d</button>
            <button type="button" class="btn btn-outline-primary" onclick="changeTimeRange('now-30d')">30d</button>
        </div>
    </div>

    <div class="row">
        <!-- Pedidos Criados -->
        <div class="col-lg-6 mb-4">
            @include('dashboard.includes.grafana-chart', [
                'dashboardId' => 'ffdjxj0awga9se',
                'panelId' => '1',
                'title' => 'Pedidos Criados',
                'height' => '300'
            ])
        </div>

        <!-- Orçamentos Criados -->
        <div class="col-lg-6 mb-4">
            @include('dashboard.includes.grafana-chart', [
                'dashboardId' => 'ffdjxj0awga9se',
                'panelId' => '2',
                'title' => 'Orçamentos Criados',
                'height' => '300'
            ])
        </div>
    </div>

    <div class="row">
        <!-- Valor Total Pedidos -->
        <div class="col-lg-6 mb-4">
            @include('dashboard.includes.grafana-chart', [
                'dashboardId' => 'ffdjxj0awga9se',
                'panelId' => '3',
                'title' => 'Valor Total Pedidos',
                'height' => '300'
            ])
        </div>

        <!-- Taxa de Conversão -->
        <div class="col-lg-6 mb-4">
            @include('dashboard.includes.grafana-chart', [
                'dashboardId' => 'ffdjxj0awga9se',
                'panelId' => '4',
                'title' => 'Taxa de Conversão',
                'height' => '300'
            ])
        </div>
    </div>
</div>

<script>
let currentTimeRange = 'now-7d';

function changeTimeRange(range) {
    currentTimeRange = range;

    // Atualiza botões
    document.querySelectorAll('.btn-group .btn').forEach(btn => {
        btn.classList.remove('active');
    });
    event.target.classList.add('active');

    // Atualiza todos os iframes
    document.querySelectorAll('iframe[id^="iframe-"]').forEach(iframe => {
        const panelId = iframe.id.replace('iframe-', '');
        const url = new URL(iframe.src);
        url.searchParams.set('from', range);
        url.searchParams.set('to', 'now');
        iframe.src = url.toString();
    });
}

function hideLoading(panelId) {
    const loading = document.getElementById('loading-' + panelId);
    if (loading) {
        loading.style.display = 'none';
    }
}

function refreshChart(panelId) {
    const iframe = document.getElementById('iframe-' + panelId);
    const loading = document.getElementById('loading-' + panelId);

    if (loading) {
        loading.style.display = 'flex';
    }

    if (iframe) {
        iframe.src = iframe.src;
    }
}
</script>
@endsection
