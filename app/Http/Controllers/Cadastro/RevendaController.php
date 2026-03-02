<?php

namespace App\Http\Controllers\Cadastro;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cadastro\Revenda\StoreRequest;
use App\Http\Requests\Cadastro\Revenda\UpdateRequest;
use App\Models\Cadastro\Revenda;
use App\Models\Cadastro\RevendaHistorico;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RevendaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $revendas = Revenda::with(['tipoRevenda', 'situacao', 'origemCadastro'])
            ->when($request->search, function ($query, $search) {
                return $query->where('nomeFantasia', 'like', "%{$search}%")
                    ->orWhere('razaoSocial', 'like', "%{$search}%")
                    ->orWhere('cnpj', 'like', "%{$search}%");
            })
            ->paginate();

        return view('cadastro.revenda.index', compact('revendas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tiposRevenda = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipos de Revenda'))->get();
        $optantesSimples = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Opção Sim / Não'))->get();
        $situacoes = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Situação'))->get();
        $tiposRegime = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Regime'))->get();
        $tiposShowroom = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Showroom'))->get();
        $origensCadastro = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Origem Cadastro'))->get();
        $matrizesRevenda = Revenda::get();
        $revenda = new Revenda;

        return view('cadastro.revenda.create', compact(
            'revenda', 'tiposRevenda', 'optantesSimples', 'situacoes', 'tiposRegime', 'tiposShowroom', 'origensCadastro', 'matrizesRevenda'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        DB::beginTransaction();

        try {
            $revenda = Revenda::create($request->validated());
            DB::commit();
            return redirect()->signedRoute('cadastro.revenda.index')->with('success', __('labels.revenda.success.created'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->signedRoute('cadastro.revenda.index')->with('error', __('labels.revenda.error.not_created') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Revenda $revenda)
    {
        $bloquearCampos = true;
        $revenda->load(['tipoRevenda', 'matrizRevenda', 'optanteSimples', 'situacao', 'tipoRegime', 'tipoShowroom', 'origemCadastro']);
        return view('cadastro.revenda.show', compact('revenda', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Revenda $revenda)
    {
        $revenda->load(['tipoRevenda', 'matrizRevenda', 'optanteSimples', 'situacao', 'tipoRegime', 'tipoShowroom', 'origemCadastro']);

        $tiposRevenda = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipos de Revenda'))->get();
        $optantesSimples = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Opção Sim / Não'))->get();
        $situacoes = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Situação'))->get();
        $tiposRegime = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Regime'))->get();
        $tiposShowroom = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Showroom'))->get();
        $origensCadastro = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Origem Cadastro'))->get();
        $matrizesRevenda = Revenda::get();


        return view('cadastro.revenda.edit', compact(
            'revenda', 'tiposRevenda', 'optantesSimples', 'situacoes', 'tiposRegime', 'tiposShowroom', 'origensCadastro', 'matrizesRevenda'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Revenda $revenda)
    {
        DB::beginTransaction();

        try {
            $revenda->update($request->validated());
            DB::commit();
            return redirect()->signedRoute('cadastro.revenda.index')->with('success', __('labels.revenda.success.updated'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->signedRoute('cadastro.revenda.index')->with('error', __('labels.revenda.error.not_updated') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Show the form for destroying the specified resource.
     */
    public function destroy(Revenda $revenda)
    {
        $bloquearCampos = true;
        $revenda->load(['tipoRevenda', 'matrizRevenda', 'optanteSimples', 'situacao', 'tipoRegime', 'tipoShowroom', 'origemCadastro']);
        return view('cadastro.revenda.destroy', compact('revenda', 'bloquearCampos'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Revenda $revenda)
    {
        try {
            $revenda->delete();
            return redirect()->signedRoute('cadastro.revenda.index')->with('success', __('labels.revenda.success.deleted'));
        } catch (\Exception $e) {
            return redirect()->signedRoute('cadastro.revenda.index')->with('error', __('labels.revenda.error.not_deleted') . ' - ' . $e->getMessage());
        }
    }

    /**
     * Display history of the specified resource.
     */
    public function history(Revenda $revenda)
    {
        $bloquearCampos = true;
        $tiposRevenda = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipos de Revenda'))->get();
        $optantesSimples = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Opção Sim / Não'))->get();
        $situacoes = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Situação'))->get();
        $tiposRegime = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Regime'))->get();
        $tiposShowroom = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Tipo de Showroom'))->get();
        $origensCadastro = PadraoTipo::whereHas('padrao', fn($q) => $q->where('descricao', 'Origem Cadastro'))->get();
        $matrizesRevenda = Revenda::get();


        $revenda->load('historicos.user', 'historicos.tipoAlteracao');
        return view('cadastro.revenda.history', compact('revenda', 'bloquearCampos', 'tiposRevenda', 'optantesSimples', 'situacoes', 'tiposRegime', 'tiposShowroom', 'origensCadastro', 'matrizesRevenda'));
    }


    public function historyDetails(Revenda $revenda, $historicoId)
    {
        if (!Auth::user()->canAccess('sistema.revenda.history')) {
            abort(403);
        }

        $historico = RevendaHistorico::findOrFail($historicoId);

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
            'revendaPai_id' => __('labels.commission.history.fields.revendaPai_id'),
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
