<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Familia\StoreRequest;
use App\Http\Requests\Cadastro\Familia\UpdateRequest;
use App\Models\Cadastro\Familia;
use App\Models\Cadastro\FamiliaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FamiliaController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.index'), 403, 'Acesso não autorizado');
        $familias = Familia::paginate();
        return view('cadastro.familia.index', compact('familias'));
    }

    public function create()
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.create'), 403, 'Acesso não autorizado');
        return view('cadastro.familia.create');
    }

    public function store(StoreRequest $request)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.store'), 403, 'Acesso não autorizado');
        $familia = Familia::create($request->validated());
        if($familia) {
            return redirect()->signedRoute('cadastro.familia.index')->with('success', __('labels.family.success.created'));
        }
        return redirect()->signedRoute('cadastro.familia.index')->with('error', __('labels.family.error.not_created'));
    }

    public function show(Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.show'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.familia.show', compact('familia', 'bloquearCampos'));
    }

    public function edit(Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.edit'), 403, 'Acesso não autorizado');
        return view('cadastro.familia.edit', compact('familia'));
    }

    public function update(UpdateRequest $request, Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.update'), 403, 'Acesso não autorizado');
        if($familia->update($request->validated())){
            return redirect()->signedRoute('cadastro.familia.index')->with('success', __('labels.family.success.updated'));
        }
        return redirect()->signedRoute('cadastro.familia.index')->with('error', __('labels.family.error.not_updated'));
    }

    public function destroy(Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.destroy'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        return view('cadastro.familia.destroy', compact('familia', 'bloquearCampos'));
    }

    public function delete(Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.destroy'), 403, 'Acesso não autorizado');
        if($familia->delete()) {
            return redirect()->signedRoute('cadastro.familia.index')->with('success', __('labels.family.success.deleted'));
        }
        return redirect()->signedRoute('cadastro.familia.index')->with('error', __('labels.family.error.not_deleted'));
    }

    public function history(Familia $familia)
    {
        abort_if (!Auth::user()->canAccess('cadastro.familia.history'), 403, 'Acesso não autorizado');
        $bloquearCampos = true;
        $familia->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.familia.history', compact('familia', 'bloquearCampos'));
    }

    public function historyDetails(Familia $familia, $historicoId)
    {
        if (!Auth::user()->canAccess('cadastro.familia.history')) {
            abort(403);
        }
        $historico = FamiliaHistorico::findOrFail($historicoId);
        $historico->load(['user', 'tipoAlteracao']);
        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;
        
        if ($dadosAnteriores) {
            foreach ($dadosAnteriores as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosAnteriores[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {}
                }
            }
        }
        if ($dadosNovos) {
            foreach ($dadosNovos as $key => $value) {
                if (in_array($key, ['created_at', 'updated_at', 'deleted_at']) && $value) {
                    try {
                        $dadosNovos[$key] = \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
                    } catch (\Exception $e) {}
                }
            }
        }

        $camposTabela = [
            'id' => __('labels.family.history.fields.id'),
            'descricao' => __('labels.family.history.fields.descricao'),
            'created_at' => __('labels.family.history.fields.created_at'),
            'updated_at' => __('labels.family.history.fields.updated_at'),
            'deleted_at' => __('labels.family.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
