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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $situacoes = PadraoTipo::all();
        return view('sistema.geracoes.create', compact('situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'classe' => 'required|string|max:100',
            'modulo_pai' => 'required|string|max:50',
            'campos' => 'required|json',
            'soft_delete' => 'required|boolean',
            'timestamps' => 'required|boolean',
            'criar_permissoes' => 'required|boolean',
            'criar_menu' => 'required|boolean',
            'observacoes' => 'nullable|string',
            'situacao_id' => 'required|exists:padrao_tipo,id'
        ]);

        Geracao::create($validated);

        return redirect()->route('geracoes.index')->with('success', 'Geração criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Geracao $geracao)
    {
        $geracao->load('situacao');
        return view('sistema.geracoes.show', compact('geracao'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Geracao $geracao)
    {
        $situacoes = PadraoTipo::all();
        return view('sistema.geracoes.edit', compact('geracao', 'situacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Geracao $geracao)
    {
        $validated = $request->validate([
            'classe' => 'required|string|max:100',
            'modulo_pai' => 'required|string|max:50',
            'campos' => 'required|json',
            'soft_delete' => 'required|boolean',
            'timestamps' => 'required|boolean',
            'criar_permissoes' => 'required|boolean',
            'criar_menu' => 'required|boolean',
            'observacoes' => 'nullable|string',
            'situacao_id' => 'required|exists:padrao_tipo,id'
        ]);

        $geracao->update($validated);

        return redirect()->route('geracoes.index')->with('success', 'Geração atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Geracao $geracao)
    {
        $situacoes = PadraoTipo::all();
        return view('sistema.geracoes.destroy', compact('geracao', 'situacoes'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Geracao $geracao)
    {
        if($geracao->delete()) {
            return redirect()->signedRoute('sistema.geracao.index')->with('success', __('labels.creator.success.deleted'));
        }
        return redirect()->signedRoute('sistema.geracao.index')->with('error', __('labels.creator.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Geracao $geracao)
    {
        return view('sistema.geracoes.history', compact('geracao'));
    }

    /**
     * Display the history details of the specified resource.
     */
    public function historyDetails(Geracao $geracao)
    {
        return view('sistema.geracoes.history-details', compact('geracao'));
    }
}
