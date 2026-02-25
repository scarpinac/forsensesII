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
            <button type="button" class="btn btn-outline-primary" onclick="refreshChart('{{ $panelId }}')">
                <i class="fas fa-sync-alt"></i>
            </button>
            <a href="http://localhost:3000/d/{{ $dashboardId }}" target="_blank" class="btn btn-outline-secondary">
                <i class="fas fa-external-link-alt"></i>
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div id="chart-{{ $panelId }}" class="chart-container" style="height: {{ $height }}px;">
            <iframe 
                id="iframe-{{ $panelId }}"
                src="http://localhost:3000/d-solo/{{ $dashboardId }}?orgId=1&panelId={{ $panelId }}&from={{ $from }}&to={{ $to }}&theme=light&kiosk=tv"
                width="100%" 
                height="{{ $height }}" 
                frameborder="0"
                onload="hideLoading('{{ $panelId }}')"
                allowfullscreen>
            </iframe>
            <div id="loading-{{ $panelId }}" class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
            </div>
        </div>
    </div>
</div>
