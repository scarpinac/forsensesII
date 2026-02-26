<?php

namespace App\Http\Controllers;

use App\Models\Sistema\Geracao;

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
