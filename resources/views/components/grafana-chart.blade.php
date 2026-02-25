@props([
    'dashboardId',
    'panelId',
    'title',
    'height' => '300',
    'from' => 'now-7d',
    'to' => 'now'
])

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
                src="http://localhost:3000/d-solo/{{ $dashboardId }}?orgId=1&panelId={{ $panelId }}&from={{ $from }}&to={{ $to }}&theme=light"
                width="100%" 
                height="{{ $height }}" 
                frameborder="0"
                onload="hideLoading('{{ $panelId }}')">
            </iframe>
            <div id="loading-{{ $panelId }}" class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="sr-only">Carregando...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
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

<style>
.chart-container {
    position: relative;
}

#loading-{{ $panelId }} {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 10;
}

#loading-{{ $panelId }}[style*="display: none"] {
    display: none !important;
}
</style>
