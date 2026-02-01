<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\PadraoTipo;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Http\Requests\Sistema\Perfil\StoreRequest;
use App\Http\Requests\Sistema\Perfil\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class PerfilController extends Controller
{
    /**
     * Organiza permissões em estrutura de árvore
     */
    private function organizarPermissoesEmArvore($permissoes)
    {
        $arvore = [];

        foreach ($permissoes as $permissao) {
            $partes = explode('.', $permissao->descricao);

            $nivelAtual = &$arvore;

            foreach ($partes as $i => $parte) {
                if (!isset($nivelAtual[$parte])) {
                    $nivelAtual[$parte] = [
                        'nome' => $parte,
                        'permissao_completa' => ($i >= 2) ? $permissao->descricao : null,
                        'filhos' => []
                    ];
                }
                $nivelAtual = &$nivelAtual[$parte]['filhos'];
            }
        }

        return $arvore;
    }

    /**
     * Renderiza a árvore de permissões como HTML com checkboxes em cascata
     */
    private function renderizarArvorePermissoes($arvore, $nivel = 0, $permissoesSelecionadas = [])
    {
        $html = '';

        foreach ($arvore as $item) {
            $identacao = '';
            $temFilhos = !empty($item['filhos']);
            $itemId = 'item_' . str_replace('.', '_', $item['nome']) . '_' . $nivel;

            if ($temFilhos) {
                // Nível pai (módulo ou recurso) - checkbox para selecionar todos os filhos
                $filhosPermissoes = $this->extrairPermissoesFilhos($item);
                $todosMarcados = !empty($filhosPermissoes) && count(array_intersect($filhosPermissoes, $permissoesSelecionadas)) === count($filhosPermissoes);
                $algumMarcado = !empty($filhosPermissoes) && count(array_intersect($filhosPermissoes, $permissoesSelecionadas)) > 0;

                $checked = $todosMarcados ? 'checked' : '';
                $indeterminate = $algumMarcado && !$todosMarcados ? 'data-indeterminate="true"' : '';

                $html .= '<div class="mb-2">';

                // Checkbox do pai com botão de expandir dentro do label
                $html .= '<div class="form-check">';

                $html .= '<label class="form-check-label d-flex align-items-center" for="' . $itemId . '_check" style="cursor: pointer;">';
                $html .= '<input class="form-check-input mr-2" type="checkbox" id="' . $itemId . '_check" ' . $checked . ' ' . $indeterminate . ' ';
                $html .= 'onchange="toggleChildren(\'' . $itemId . '\', this.checked)">';
                $html .= '<button type="button" class="btn btn-sm btn-link p-0 mr-2" onclick="toggleCollapse(\'' . $itemId . '\')">';
                $html .= '<i class="fas fa-chevron-down" id="' . $itemId . '_icon"></i>';
                $html .= '</button>';
                $html .= '<strong>' . ucfirst($item['nome']) . '</strong>';
                $html .= '</label>';
                $html .= '</div>';

                // Container dos filhos (colapsável)
                $html .= '<div id="' . $itemId . '_children" class="ml-4" style="display: block;">';
                $html .= $this->renderizarArvorePermissoes($item['filhos'], $nivel + 1, $permissoesSelecionadas);
                $html .= '</div>';
                $html .= '</div>';
            } else {
                // Nível folha (ação específica) - checkbox normal
                // Extrair ID da permissão da descrição completa
                $permissaoId = $this->extrairIdPermissao($item['permissao_completa']);
                $checked = in_array($permissaoId, $permissoesSelecionadas) ? 'checked' : '';

                $html .= $identacao . '<div class="form-check mb-1">';
                $html .= '<label class="form-check-label" for="permissao_' . str_replace('.', '_', $item['permissao_completa']) . '">';
                $html .= '<input class="form-check-input mr-2" type="checkbox" name="permissoes[]" value="' . $permissaoId . '" id="permissao_' . str_replace('.', '_', $item['permissao_completa']) . '" ' . $checked . '>';
                $html .= '<i class="fas fa-cog text-muted"></i> ' . $item['nome'] . '</label>';
                $html .= '</div>';
            }
        }

        return $html;
    }

    /**
     * Extrai ID da permissão da descrição completa
     */
    private function extrairIdPermissao($permissaoDescricao)
    {
        // Buscar a permissão no banco para obter o ID
        static $cache = [];

        if (!isset($cache[$permissaoDescricao])) {
            $permissao = Permissao::where('descricao', $permissaoDescricao)->first();
            $cache[$permissaoDescricao] = $permissao ? $permissao->id : null;
        }

        return $cache[$permissaoDescricao];
    }

    /**
     * Busca o ID do tipo de alteração pela descrição
     */
    private function getTipoAlteracaoId(string $descricao): ?int
    {
        static $cache = [];

        if (!isset($cache[$descricao])) {
            $tipo = PadraoTipo::where('descricao', $descricao)->first();
            $cache[$descricao] = $tipo ? $tipo->id : null;
        }

        return $cache[$descricao];
    }

    /**
     * Extrai todas as permissões dos filhos de um item
     */
    private function extrairPermissoesFilhos($item)
    {
        $permissoes = [];

        foreach ($item['filhos'] as $filho) {
            if ($filho['permissao_completa']) {
                // Extrair ID da permissão em vez da descrição
                $permissaoId = $this->extrairIdPermissao($filho['permissao_completa']);
                if ($permissaoId) {
                    $permissoes[] = $permissaoId;
                }
            }
            if (!empty($filho['filhos'])) {
                $permissoes = array_merge($permissoes, $this->extrairPermissoesFilhos($filho));
            }
        }

        return $permissoes;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!Auth::user()->canAccess('sistema.perfil.index')) {
            abort(403);
        }

        $perfis = Perfil::paginate();

        return view('sistema.perfil.index', compact('perfis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::user()->canAccess('sistema.perfil.create')) {
            abort(403);
        }

        // Carregar todas as permissões não deletadas
        $permissoes = Permissao::whereNull('deleted_at')->orderBy('descricao')->get();
        $arvorePermissoes = $this->organizarPermissoesEmArvore($permissoes);
        $htmlPermissoes = $this->renderizarArvorePermissoes($arvorePermissoes);

        return view('sistema.perfil.create', compact('htmlPermissoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        if (!Auth::user()->canAccess('sistema.perfil.store')) {
            abort(403);
        }

        $perfil = Perfil::create($request->validated());

        if($perfil) {
            // Associar permissões ao perfil usando IDs diretamente
            $permissoesAdicionadas = [];
            if ($request->has('permissoes')) {
                foreach ($request->permissoes as $permissaoId) {
                    $perfil->perfilPermissoes()->create([
                        'permissao_id' => $permissaoId
                    ]);

                    // Buscar descrição da permissão para histórico
                    $permissao = Permissao::find($permissaoId);
                    if ($permissao) {
                        $permissoesAdicionadas[] = [
                            'id' => $permissaoId,
                            'descricao' => $permissao->descricao
                        ];
                    }
                }
            }

            // Registrar histórico das permissões se foram adicionadas
            if (!empty($permissoesAdicionadas)) {
                $perfil->historicos()->create([
                    'user_id' => Auth::id(),
                    'dados_anteriores' => ['permissoes' => []],
                    'dados_novos' => ['permissoes' => $permissoesAdicionadas],
                    'tipoAlteracao_id' => $this->getTipoAlteracaoId('Inclusão/Remoção de Permissões'),
                ]);
            }

            return redirect()->signedRoute('sistema.perfil.index')->with('success', __('labels.access_level.success.created'));
        }
        return redirect()->signedRoute('sistema.perfil.index')->with('error', __('labels.access_level.error.not_saved'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.show')) {
            abort(403);
        }

        $bloquearCampos = true;

        return view('sistema.perfil.show', compact('perfil', 'bloquearCampos'));
    }

    public function destroy(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.destroy')) {
            abort(403);
        }

        $bloquearCampos = true;

        return view('sistema.perfil.destroy', compact('perfil', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.edit')) {
            abort(403);
        }

        // Carregar todas as permissões não deletadas
        $permissoes = Permissao::whereNull('deleted_at')->orderBy('descricao')->get();
        $arvorePermissoes = $this->organizarPermissoesEmArvore($permissoes);

        // Carregar permissões já associadas ao perfil (como IDs)
        $permissoesSelecionadas = $perfil->perfilPermissoes()
            ->pluck('permissao_id')
            ->toArray();

        $htmlPermissoes = $this->renderizarArvorePermissoes($arvorePermissoes, 0, $permissoesSelecionadas);

        return view('sistema.perfil.edit', compact('perfil', 'htmlPermissoes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.update')) {
            abort(403);
        }

        $updated = $perfil->update($request->validated());

        if($updated) {
            // Capturar permissões antigas antes de remover
            $permissoesAntigas = $perfil->perfilPermissoes()
                ->with('permissao')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->permissao_id,
                        'descricao' => $item->permissao->descricao
                    ];
                })
                ->toArray();

            // Remover todas as permissões existentes
            $perfil->perfilPermissoes()->delete();

            // Adicionar novas permissões usando IDs diretamente
            $novasPermissoes = [];
            if ($request->has('permissoes')) {
                foreach ($request->permissoes as $permissaoId) {
                    $perfil->perfilPermissoes()->create([
                        'permissao_id' => $permissaoId
                    ]);

                    // Buscar descrição da nova permissão
                    $permissao = Permissao::find($permissaoId);
                    if ($permissao) {
                        $novasPermissoes[] = [
                            'id' => $permissaoId,
                            'descricao' => $permissao->descricao
                        ];
                    }
                }
            }

            if ($permissoesAntigas !== $novasPermissoes) {
                $perfil->historicos()->create([
                    'user_id' => Auth::id(),
                    'dados_anteriores' => ['permissoes' => $permissoesAntigas],
                    'dados_novos' => ['permissoes' => $novasPermissoes],
                    'tipoAlteracao_id' => $this->getTipoAlteracaoId('Inclusão/Remoção de Permissões'),
                ]);
            }

            return redirect()->signedRoute('sistema.perfil.index')->with('success', __('labels.access_level.success.updated'));
        }
        return redirect()->signedRoute('sistema.perfil.index')->with('error', __('labels.access_level.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.delete')) {
            abort(403);
        }

        if($perfil->delete()) {
            return redirect()->signedRoute('sistema.perfil.index')->with('success', __('labels.access_level.success.deleted'));
        }
        return redirect()->signedRoute('sistema.perfil.index')->with('error', __('labels.access_level.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Perfil $perfil)
    {
        if (!Auth::user()->canAccess('sistema.perfil.history')) {
            abort(403);
        }

        $perfil->load(['historicos.user', 'historicos.tipoAlteracao']);
        $bloquearCampos = true;
        return view('sistema.perfil.history', compact('perfil', 'bloquearCampos'));
    }

    /**
     * Get the details of a specific history record.
     */
    public function historyDetails(Perfil $perfil, $historico)
    {
        if (!Auth::user()->canAccess('sistema.perfil.history')) {
            abort(403);
        }

        $historico = $perfil->historicos()->with(['user', 'tipoAlteracao'])->findOrFail($historico);

        $dadosAnteriores = $historico->dados_anteriores;
        $dadosNovos = $historico->dados_novos;

        // Tratar permissões de forma especial
        if (isset($dadosAnteriores['permissoes'])) {
            $dadosAnteriores['permissoes'] = implode(', ', array_column($dadosAnteriores['permissoes'], 'descricao'));
        }
        if (isset($dadosNovos['permissoes'])) {
            $dadosNovos['permissoes'] = implode(', ', array_column($dadosNovos['permissoes'], 'descricao'));
        }

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
            'id' => __('labels.access_level.history.fields.id'),
            'descricao' => __('labels.access_level.history.fields.descricao'),
            'permissoes' => __('labels.access_level.history.fields.permissoes'),
            'created_at' => __('labels.access_level.history.fields.created_at'),
            'updated_at' => __('labels.access_level.history.fields.updated_at'),
            'deleted_at' => __('labels.access_level.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }
}
