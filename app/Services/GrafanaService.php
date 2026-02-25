<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class GrafanaService
{
    protected $baseUrl;
    protected $apiKey;

    public function __construct()
    {
        $this->baseUrl = config('services.grafana.url', 'http://localhost:3000');
        $this->apiKey = config('services.grafana.api_key', 'admin:admin123');
    }

    /**
     * Obtém lista de dashboards
     */
    public function getDashboards()
    {
        $response = Http::withBasicAuth('admin', 'admin123')
            ->get("{$this->baseUrl}/api/search");

        if ($response->successful()) {
            return array_map(function ($dashboard) {
                return [
                    'id' => $dashboard['uid'],
                    'title' => $dashboard['title']
                ];
            }, $response->json());
        }

        return [];
    }

    /**
     * Obtém detalhes do dashboard
     */
    public function getDashboard($dashboardId)
    {
        $response = Http::withBasicAuth('admin', 'admin123')
            ->get("{$this->baseUrl}/api/dashboards/uid/{$dashboardId}");

        if ($response->successful()) {
            $dashboard = $response->json()['dashboard'];
            return [
                'id' => $dashboard['uid'],
                'title' => $dashboard['title'],
                'panels' => $dashboard['panels'] ?? []
            ];
        }

        return null;
    }

    /**
     * Obtém painéis do dashboard
     */
    public function getDashboardPanels($dashboardId)
    {
        $dashboard = $this->getDashboard($dashboardId);
        
        if (!$dashboard) {
            return [];
        }

        return array_map(function ($panel) use ($dashboardId) {
            return [
                'id' => $panel['id'],
                'title' => $panel['title'],
                'type' => $panel['type'],
                'dashboardId' => $dashboardId
            ];
        }, $dashboard['panels']);
    }
    public function getPanelData($dashboardId, $panelId, $from = 'now-7d', $to = 'now')
    {
        $cacheKey = "grafana_panel_{$dashboardId}_{$panelId}_{$from}_{$to}";
        
        return Cache::remember($cacheKey, 300, function () use ($dashboardId, $panelId, $from, $to) {
            $response = Http::withBasicAuth('admin', 'admin123')
                ->get("{$this->baseUrl}/api/datasources/proxy/1/query", [
                    'db' => 'forsensesii',
                    'q' => $this->getPanelQuery($panelId),
                    'from' => $from,
                    'to' => $to,
                    'epoch' => 'ms'
                ]);

            if ($response->successful()) {
                return $this->formatData($response->json());
            }

            return null;
        });
    }

    /**
     * Obtém URL do painel para iframe
     */
    public function getPanelUrl($dashboardId, $panelId, $from = 'now-7d', $to = 'now', $theme = 'light')
    {
        return "{$this->baseUrl}/d-solo/{$dashboardId}?orgId=1&panelId={$panelId}&from={$from}&to={$to}&theme={$theme}";
    }

    /**
     * Formata dados para exibição
     */
    protected function formatData($data)
    {
        if (!isset($data['data']['result'])) {
            return [];
        }

        $formatted = [];
        foreach ($data['data']['result'] as $series) {
            $formatted[] = [
                'name' => $series['metric']['__name__'] ?? 'Unknown',
                'data' => $series['values'] ?? []
            ];
        }

        return $formatted;
    }

    /**
     * Queries específicas para cada painel
     */
    protected function getPanelQuery($panelId)
    {
        $queries = [
            1 => "SELECT UNIX_TIMESTAMP(DATE(created_at)) as time, COUNT(*) as value FROM pedidos WHERE $__timeFilter(created_at) GROUP BY DATE(created_at)",
            2 => "SELECT UNIX_TIMESTAMP(DATE(created_at)) as time, COUNT(*) as value FROM orcamentos WHERE $__timeFilter(created_at) GROUP BY DATE(created_at)",
            3 => "SELECT UNIX_TIMESTAMP(DATE(created_at)) as time, SUM(valor) as value FROM pedidos WHERE $__timeFilter(created_at) GROUP BY DATE(created_at)",
            4 => "SELECT UNIX_TIMESTAMP(DATE(o.created_at)) as time, (COUNT(p.id) / COUNT(o.id) * 100) as value FROM orcamentos o LEFT JOIN pedidos p ON o.id = p.orcamento_id WHERE $__timeFilter(o.created_at) GROUP BY DATE(o.created_at)"
        ];

        return $queries[$panelId] ?? '';
    }
}
