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

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::paginate();
        return view('sistema.menu.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menus = Menu::whereNull('menuPai_id')->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.menu.create', compact('menus', 'permissoes', 'situacoes'));
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
        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        $bloquearCampos = true;

        return view('sistema.menu.show', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        return view('sistema.menu.edit', compact('menu', 'menus', 'permissoes', 'situacoes'));
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
        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        $bloquearCampos = true;

        return view('sistema.menu.destroy', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos'));
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
        $menus = Menu::whereNull('menuPai_id')->where('id', '!=', $menu->id)->orderBy('descricao')->get();
        $permissoes = Permissao::orderBy('descricao')->get();
        $situacaoPadrao = Padrao::where('descricao', 'Situação')->first();
        $situacoes = $situacaoPadrao ? $situacaoPadrao->tipos()->orderBy('descricao')->get() : collect();

        $bloquearCampos = true;

        return view('sistema.menu.history', compact('menu', 'menus', 'permissoes', 'situacoes', 'bloquearCampos'));
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

}
