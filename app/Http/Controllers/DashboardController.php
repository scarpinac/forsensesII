<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function index()
    {
        // Dados mockados para os cards
        $stats = [
            'products' => [
                'total' => 1248,
                'active' => 1156,
                'inactive' => 92,
                'growth' => 12.5
            ],
            'customers' => [
                'total' => 3456,
                'active' => 2890,
                'new_this_month' => 156,
                'growth' => 8.3
            ],
            'quotes' => [
                'total' => 892,
                'pending' => 234,
                'approved' => 456,
                'rejected' => 202,
                'growth' => -5.2
            ],
            'orders' => [
                'total' => 756,
                'pending' => 123,
                'processing' => 234,
                'completed' => 345,
                'cancelled' => 54,
                'growth' => 15.7
            ]
        ];

        // Dados para gráficos
        $charts = [
            'monthly_revenue' => [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                'quotes' => [45000, 52000, 48000, 61000, 58000, 67000, 72000, 69000, 75000, 71000, 78000, 82000],
                'orders' => [38000, 44000, 41000, 52000, 49000, 56000, 61000, 58000, 63000, 59000, 65000, 69000]
            ],
            'product_categories' => [
                'labels' => ['Eletrônicos', 'Vestuário', 'Alimentos', 'Móveis', 'Livros', 'Outros'],
                'data' => [35, 25, 20, 10, 7, 3]
            ],
            'customer_segments' => [
                'labels' => ['VIP', 'Premium', 'Regular', 'Novos'],
                'data' => [15, 25, 45, 15]
            ],
            'order_status' => [
                'labels' => ['Pendente', 'Processando', 'Concluído', 'Cancelado'],
                'data' => [123, 234, 345, 54]
            ]
        ];

        return view('dashboard', compact('stats', 'charts'));
    }
}
