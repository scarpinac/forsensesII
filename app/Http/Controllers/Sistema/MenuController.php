<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuHistorico;
use App\Models\Padrao;
use App\Models\Permissao;
use App\Http\Requests\Sistema\Menu\StoreRequest;
use App\Http\Requests\Sistema\Menu\UpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.index'), 403, 'Acesso não autorizado');

        $menus = Menu::paginate();
        return view('sistema.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.create'), 403, 'Acesso não autorizado');

        $menus = Menu::whereNull('menuPai_id')->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        // Carregar todos os menus não deletados para a árvore
        $todosMenus = Menu::whereNull('deleted_at')->orderBy('descricao')->get();
        $arvoreMenus = $this->organizarMenusEmArvore($todosMenus);
        $htmlMenus = $this->renderizarArvoreMenus($arvoreMenus);

        return view('sistema.menu.create', compact('menus', 'permissoes', 'situacoes', 'htmlMenus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $menu = Menu::create($request->validated());

        if($menu) {
            return redirect()->signedRoute('sistema.menu.index')->with('success', __('labels.menu.success.created'));
        }
        return redirect()->signedRoute('sistema.menu.index')->with('error', __('labels.menu.error.not_saved'));

    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.show'), 403, 'Acesso não autorizado');

        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        // Carregar menu pai para exibição quando campos estão bloqueados
        $menuPai = $menu->menuPai_id ? Menu::find($menu->menuPai_id) : null;

        $bloquearCampos = true;

        return view('sistema.menu.show', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos', 'menuPai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.edit'), 403, 'Acesso não autorizado');

        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        // Carregar todos os menus não deletados para a árvore
        $todosMenus = Menu::whereNull('deleted_at')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $arvoreMenus = $this->organizarMenusEmArvore($todosMenus);
        $htmlMenus = $this->renderizarArvoreMenus($arvoreMenus, 0, $menu->menuPai_id);

        return view('sistema.menu.edit', compact('menu', 'menus', 'permissoes', 'situacoes', 'htmlMenus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, Menu $menu)
    {
        if($menu->update($request->validated())) {
            return redirect()->signedRoute('sistema.menu.index')->with('success', __('labels.menu.success.updated'));
        }
        return redirect()->signedRoute('sistema.menu.index')->with('error', __('labels.menu.error.not_updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.destroy'), 403, 'Acesso não autorizado');

        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        $menuPai = $menu->menuPai_id ? Menu::find($menu->menuPai_id) : null;

        $bloquearCampos = true;

        return view('sistema.menu.destroy', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos', 'menuPai'));
    }

    /**
     * Delete the specified resource from storage.
     */
    public function delete(Menu $menu)
    {
        if($menu->delete()) {
            return redirect()->signedRoute('sistema.menu.index')->with('success', __('labels.menu.success.deleted'));
        }
        return redirect()->signedRoute('sistema.menu.index')->with('error', __('labels.menu.error.not_deleted'));
    }

    /**
     * Display the history of the specified resource.
     */
    public function history(Menu $menu)
    {
        abort_if (!Auth::user()->canAccess('sistema.menu.history'), 403, 'Acesso não autorizado');

        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        // Carregar menu pai para exibição quando campos estão bloqueados
        $menuPai = $menu->menuPai_id ? Menu::find($menu->menuPai_id) : null;

        $bloquearCampos = true;

        return view('sistema.menu.history', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos', 'menuPai'));
    }

    /**
     * Show the details of a specific history record.
     */
    public function historyDetails(Menu $menu, $historicoId)
    {
        $historico = MenuHistorico::findOrFail($historicoId);

        if ($historico->menu_id !== $menu->id) {
            abort(403, 'Ação não autorizada.');
        }

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
            'id' => __('labels.menu.history.fields.id'),
            'descricao' => __('labels.menu.history.fields.descricao'),
            'icone' => __('labels.menu.history.fields.icone'),
            'rota' => __('labels.menu.history.fields.rota'),
            'menuPai_id' => __('labels.menu.history.fields.menuPai_id'),
            'permissao_id' => __('labels.menu.history.fields.permissao_id'),
            'situacao_id' => __('labels.menu.history.fields.situacao_id'),
            'created_at' => __('labels.menu.history.fields.created_at'),
            'updated_at' => __('labels.menu.history.fields.updated_at'),
            'deleted_at' => __('labels.menu.history.fields.deleted_at'),
        ];

        return response()->json([
            'historico' => $historico,
            'dadosAnteriores' => $dadosAnteriores,
            'dadosNovos' => $dadosNovos,
            'camposTabela' => $camposTabela,
        ]);
    }

    /**
     * Organiza os menus em estrutura de árvore
     */
    private function organizarMenusEmArvore($menus)
    {
        $arvore = [];
        $menuMap = [];

        // Primeiro, criar mapa de todos os menus
        foreach ($menus as $menu) {
            $menuMap[$menu->id] = [
                'id' => $menu->id,
                'descricao' => $menu->descricao,
                'icone' => $menu->icone,
                'rota' => $menu->rota,
                'menuPai_id' => $menu->menuPai_id,
                'filhos' => []
            ];
        }

        // Depois, organizar em árvore
        foreach ($menuMap as $id => $menu) {
            if ($menu['menuPai_id'] && isset($menuMap[$menu['menuPai_id']])) {
                $menuMap[$menu['menuPai_id']]['filhos'][] = &$menuMap[$id];
            } else {
                $arvore[] = &$menuMap[$id];
            }
        }

        return $arvore;
    }

    /**
     * Renderiza a árvore de menus como HTML com radio buttons
     */
    private function renderizarArvoreMenus($arvore, $nivel = 0, $menuSelecionadoId = null)
    {
        $html = '';

        foreach ($arvore as $item) {
            $temFilhos = !empty($item['filhos']);
            $itemId = 'menu_' . $item['id'] . '_' . $nivel;

            // Calcular indentação baseada no nível
            $indentacao = $nivel * 40; // 20px por nível
            $estiloIndentacao = $nivel > 0 ? 'style="margin-left: ' . $indentacao . 'px;"' : '';

            if ($temFilhos) {
                // Nível pai - radio button para selecionar este menu
                $checked = ($menuSelecionadoId == $item['id']) ? 'checked' : '';

                $html .= '<div class="mb-1" ' . $estiloIndentacao . '>';

                // Radio button do pai com botão de expandir
                $html .= '<div class="form-check">';

                $html .= '<label class="form-check-label d-flex align-items-center" for="' . $itemId . '_radio" style="cursor: pointer;">';
                $html .= '<button type="button" class="btn btn-sm btn-link p-0 mr-2" onclick="toggleCollapse(\'' . $itemId . '\', event)" style="flex-shrink: 0;">';
                $html .= '<i class="fas fa-chevron-down" id="' . $itemId . '_icon"></i>';
                $html .= '</button>';
                $html .= '<input class="form-check-input mr-2" type="radio" name="menuPai_id" value="' . $item['id'] . '" id="' . $itemId . '_radio" ' . $checked . '>';
                $html .= '<strong>' . $item['descricao'] . '</strong>';
                $html .= '</label>';

                $html .= '</div>';

                // Container dos filhos (colapsável)
                $html .= '<div id="' . $itemId . '_children" style="display: block;">';
                $html .= $this->renderizarArvoreMenus($item['filhos'], $nivel + 1, $menuSelecionadoId);
                $html .= '</div>';
                $html .= '</div>';
            } else {
                // Nível folha - radio button normal
                $checked = ($menuSelecionadoId == $item['id']) ? 'checked' : '';

                $html .= '<div class="form-check mb-1" ' . $estiloIndentacao . '>';
                $html .= '<label class="form-check-label" for="' . $itemId . '_radio">';
                $html .= '<input class="form-check-input mr-2" type="radio" name="menuPai_id" value="' . $item['id'] . '" id="' . $itemId . '_radio" ' . $checked . '>';
                $html .= $item['descricao'];
                if ($item['rota']) {
                    $html .= ' <small class="text-muted">(' . $item['rota'] . ')</small>';
                }
                $html .= '</label>';
                $html .= '</div>';
            }
        }

        return $html;
    }

}
