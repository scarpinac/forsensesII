<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Cor\StoreRequest;
use App\Http\Requests\Cadastro\Cor\UpdateRequest;
use App\Models\Cadastro\Cor;
use App\Models\Cadastro\CorHistorico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.index'), 403, 'Acesso não autorizado');

        $cores = Cor::paginate();

        return view('cadastro.cor.index', compact('cores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.create'), 403, 'Acesso não autorizado');

        return view('cadastro.cor.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.store'), 403, 'Acesso não autorizado');

        $cor = Cor::create($request->validated());

        if($cor) {
            return redirect()->signedRoute('cadastro.cor.index')->with('success', __('labels.color.success.created'));
        }
        return redirect()->signedRoute('cadastro.cor.index')->with('error', __('labels.color.error.not_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.cor.show', compact('cor', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.edit'), 403, 'Acesso não autorizado');

        return view('cadastro.cor.edit', compact('cor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.update'), 403, 'Acesso não autorizado');

        if($cor->update($request->validated())){
            return redirect()->signedRoute('cadastro.cor.index')->with('success', __('labels.color.success.updated'));
        }
        return redirect()->signedRoute('cadastro.cor.index')->with('success', __('labels.color.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('cadastro.cor.destroy', compact('cor', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage permanently.
     */
    public function delete(Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.destroy'), 403, 'Acesso não autorizado');

        if($cor->delete()) {
            return redirect()->signedRoute('cadastro.cor.index')->with('success', __('labels.color.success.deleted'));
        }

        return redirect()->signedRoute('cadastro.cor.index')->with('error', __('labels.color.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Cor $cor)
    {
        abort_if (!Auth::user()->canAccess('cadastro.cor.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $cor->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.cor.history', compact('cor', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Cor $cor, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.cor.history')) {
            abort(403);
        }

        $historico = CorHistorico::findOrFail($historicoId);

        $historico->load(['user', 'tipoAlteracao']);

        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {
                        // Mantém o valor original se não conseguir parsear
                    }
                }
            }
        }

        // Mapeamento de campos para exibição amigável baseado no idioma atual
        $camposTabela = [
            'id' => __('labels.color.history.fields.id'),
            'descricao' => __('labels.color.history.fields.descricao'),
            'corHexadecimal' => __('labels.color.history.fields.corHexadecimal'),
            'created_at' => __('labels.color.history.fields.created_at'),
            'updated_at' => __('labels.color.history.fields.updated_at'),
            'deleted_at' => __('labels.color.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
