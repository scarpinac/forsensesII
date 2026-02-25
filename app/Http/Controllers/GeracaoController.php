<?php

namespace App\Http\Controllers;

use App\Models\Geracao;
use App\Models\Menu;
use App\Models\Padrao;
use App\Models\PadraoTipo;
use App\Models\Permissao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeracaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $geracoes = Geracao::with('situacao')->paginate();
        return view('sistema.geracoes.index', compact('geracoes'));
    }

}
