<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Transportadora\StoreRequest;
use App\Http\Requests\Cadastro\Transportadora\UpdateRequest;
use App\Models\Cadastro\Transportadora;
use App\Models\Cadastro\TransportadoraHistorico;
use Illuminate\Support\Facades\Auth;

class TransportadoraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.index'), 403, 'Acesso não autorizado');

        $transportadoras = Transportadora::with('situacao')->paginate();

        return view('cadastro.transportadora.index', compact('transportadoras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.create'), 403, 'Acesso não autorizado');

        $situacoes = \App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::Situacoes)->get();

        return view('cadastro.transportadora.create', compact('situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.store'), 403, 'Acesso não autorizado');

        $data = $request->validated();

        if ($request->has('estadosAtendidos')) {
            $data['estadosAtendidos'] = implode(',', $request->input('estadosAtendidos'));
        }

        $transportadora = Transportadora::create($data);

        if ($transportadora) {
            return redirect()->signedRoute('cadastro.transportadora.index')->with('success', __('labels.transportadora.success.created'));
        }
        return redirect()->signedRoute('cadastro.transportadora.index')->with('error', __('labels.transportadora.error.not_created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $transportadora->load('situacao');
        return view('cadastro.transportadora.show', compact('transportadora', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.edit'), 403, 'Acesso não autorizado');

        $transportadora->load('situacao');
        $situacoes = \App\Models\Sistema\PadraoTipo::where('padrao_id', \App\Models\Sistema\Padrao::Situacoes)->get();

        return view('cadastro.transportadora.edit', compact('transportadora', 'situacoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.update'), 403, 'Acesso não autorizado');

        $data = $request->validated();

        if ($request->has('estadosAtendidos')) {
            $data['estadosAtendidos'] = implode(',', $request->input('estadosAtendidos'));
        }

        if ($transportadora->update($data)) {
            return redirect()->signedRoute('cadastro.transportadora.index')->with('success', __('labels.transportadora.success.updated'));
        }
        return redirect()->signedRoute('cadastro.transportadora.index')->with('error', __('labels.transportadora.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.destroy'), 403, 'Acesso não autorizado');

        $bloquearCampos = true;
        return view('cadastro.transportadora.destroy', compact('transportadora', 'bloquearCampos'));
    }

    /**
     * Delete the specified resource from storage permanently.
     */
    public function delete(Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.destroy'), 403, 'Acesso não autorizado');

        if ($transportadora->delete()) {
            return redirect()->signedRoute('cadastro.transportadora.index')->with('success', __('labels.transportadora.success.deleted'));
        }

        return redirect()->signedRoute('cadastro.transportadora.index')->with('error', __('labels.transportadora.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Transportadora $transportadora)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $transportadora->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.transportadora.history', compact('transportadora', 'bloquearCampos'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Transportadora $transportadora, $historicoId)
    {
        abort_if (!Auth::user()->canAccess('cadastro.transportadora.history'), 403, 'Acesso não autorizado');

        $historico = TransportadoraHistorico::findOrFail($historicoId);

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
            'id' => __('labels.transportadora.history.fields.id'),
            'nomeFantasia' => __('labels.transportadora.history.fields.nomeFantasia'),
            'razaoSocial' => __('labels.transportadora.history.fields.razaoSocial'),
            'cnpj' => __('labels.transportadora.history.fields.cnpj'),
            'situacao_id' => __('labels.transportadora.history.fields.situacao_id'),
            'observacao' => __('labels.transportadora.history.fields.observacao'),
            'estadosAtendidos' => __('labels.transportadora.history.fields.estadosAtendidos'),
            'created_at' => __('labels.transportadora.history.fields.created_at'),
            'updated_at' => __('labels.transportadora.history.fields.updated_at'),
            'deleted_at' => __('labels.transportadora.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
