<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the dashboard view.
     */
    public function index()
    {
        // Métricas gerais do sistema
        $stats = [
            'users' => [
                'total' => DB::table('users')->count(),
                'active' => DB::table('users')->where('situacao_id', 1)->count(),
                'new_this_month' => DB::table('users')->whereMonth('created_at', now()->month)->count(),
            ],
            'gerador_cadastros' => [
                'total' => DB::table('gerador_cadastros')->count(),
                'active' => DB::table('gerador_cadastros')->count(),
            ],
            'parametros' => [
                'total' => DB::table('parametro')->count(),
            ],
            'menus' => [
                'total' => DB::table('menu')->count(),
                'active' => DB::table('menu')->where('situacao_id', 1)->count(),
            ]
        ];

        return view('dashboard', compact('stats'));
    }

    /**
     * API endpoint para dados do dashboard
     */
    public function dadosGraficos(Request $request)
    {
        return response()->json([
            'message' => 'Funcionalidade de gráficos desabilitada'
        ]);
    }

}
