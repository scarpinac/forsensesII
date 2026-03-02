<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Comissao\StoreRequest;
use App\Http\Requests\Cadastro\Comissao\UpdateRequest;
use App\Models\Cadastro\Comissao;
use App\Models\Cadastro\ComissaoHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComissaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.index'), 403, 'Acesso não autorizado');

        $comissoes = Comissao::with('tipoComissao')->paginate();
        $tiposComissao = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Comissão');
        })->get();

        return view('cadastro.comissao.index', compact('comissoes', 'tiposComissao'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.create'), 403, 'Acesso não autorizado');

        $tiposComissao = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Comissão');
        })->get();

        return view('cadastro.comissao.create', compact('tiposComissao'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.store'), 403, 'Acesso não autorizado');

        $comissao = Comissao::create($request->validated());

        if($comissao) {
            return redirect()->signedRoute('cadastro.comissao.index')->with('success', __('labels.commission.success.created'));
        }
        return redirect()->signedRoute('cadastro.comissao.index')->with('error', __('labels.commission.error.not_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $comissao->load('tipoComissao');
        return view('cadastro.comissao.show', compact('comissao', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.edit'), 403, 'Acesso não autorizado');

        $comissao->load('tipoComissao');
        $tiposComissao = PadraoTipo::whereHas('padrao', function($query) {
            $query->where('descricao', 'Tipos de Comissão');
        })->get();

        return view('cadastro.comissao.edit', compact('comissao', 'tiposComissao'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.update'), 403, 'Acesso não autorizado');

        if($comissao->update($request->validated())){
            return redirect()->signedRoute('cadastro.comissao.index')->with('success', __('labels.commission.success.updated'));
        }
        return redirect()->signedRoute('cadastro.comissao.index')->with('success', __('labels.commission.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('sistema.comissao.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('cadastro.comissao.destroy', compact('comissao', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage permanently.
     */
    public function delete(Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.destroy'), 403, 'Acesso não autorizado');

        if($comissao->delete()) {
            return redirect()->signedRoute('cadastro.comissao.index')->with('success', __('labels.commission.success.deleted'));
        }

        return redirect()->signedRoute('cadastro.comissao.index')->with('error', __('labels.commission.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Comissao $comissao)
    {
        abort_if (!Auth::user()->canAccess('cadastro.comissao.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $comissao->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.comissao.history', compact('comissao', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Comissao $comissao, $historicoId)
    {
        if (!Auth::user()->canAccess('sistema.comissao.history')) {
            abort(403);
        }

        $historico = ComissaoHistorico::findOrFail($historicoId);

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
            'id' => __('labels.commission.history.fields.id'),
            'descricao' => __('labels.commission.history.fields.descricao'),
            'icone' => __('labels.commission.history.fields.icone'),
            'rota' => __('labels.commission.history.fields.rota'),
            'comissaoPai_id' => __('labels.commission.history.fields.comissaoPai_id'),
            'permissao_id' => __('labels.commission.history.fields.permissao_id'),
            'situacao_id' => __('labels.commission.history.fields.situacao_id'),
            'created_at' => __('labels.commission.history.fields.created_at'),
            'updated_at' => __('labels.commission.history.fields.updated_at'),
            'deleted_at' => __('labels.commission.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
