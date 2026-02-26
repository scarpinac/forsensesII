<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Http\Requests\Sistema\GeradorCadastros\StoreRequest;
use App\Http\Requests\Sistema\GeradorCadastros\UpdateRequest;
use App\Models\Sistema\GeradorCadastroCampo;
use App\Models\Sistema\GeradorCadastros;
use App\Models\Sistema\Padrao;
use App\Models\Sistema\PadraoTipo;
use App\Services\GeradorService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeradorCadastrosController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $geradores = GeradorCadastros::with('menu')->paginate(10);
        return view('sistema.gerador.index', compact('geradores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $arTabelas = $this->retornaTabelasSistema();
        $menusPai = $this->retornaMenusPai();
        $tiposCampos = PadraoTipo::where('padrao_id', '=', Padrao::TipoCamposGerador)->get();

        $gerador = new GeradorCadastros;

        // Passar campos antigos se houver erro de validação
        $camposAntigos = old('campos', []);

        return view('sistema.gerador.create', compact('gerador', 'menusPai', 'arTabelas', 'tiposCampos', 'camposAntigos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        Log::info('STORE METHOD INICIADO');
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Criar o gerador
            $gerador = GeradorCadastros::create([
                'classe' => $validated['classe'],
                'menuPai_id' => $validated['menuPai_id'] ?? null,
                'criar_permissoes' => $validated['criar_permissoes'] ?? false,
                'criar_menu' => $validated['criar_menu'] ?? false,
                'soft_delete' => $validated['soft_delete'] ?? false,
            ]);

            // Salvar os campos dinâmicos
            if (isset($validated['campos']) && is_array($validated['campos'])) {
                foreach ($validated['campos'] as $campoData) {
                    if (!empty($campoData['nome']) && !empty($campoData['tipo'])) {
                        GeradorCadastroCampo::create([
                            'gerador_id' => $gerador->id,
                            'nome' => $campoData['nome'],
                            'tipo_id' => $campoData['tipo'],
                            'tamanho_maximo' => $campoData['max'] ?? null,
                            'relacionamento' => $campoData['relacionamento'] ?? null,
                            'obrigatorio' => $campoData['obrigatorio'] ?? false,
                            'unico' => $campoData['unique'] ?? false,
                        ]);
                    }
                }
            }

            DB::commit();

            // Chamar o GeradorService após o commit
            Log::info('=== INICIANDO GERADOR SERVICE ===');
            $geradorService = new GeradorService();
            $resultado = $geradorService->gerarCadastro($this->prepararDadosParaGerador($gerador));
            Log::info('=== RESULTADO GERADOR SERVICE: ' . json_encode($resultado));

            return redirect()
                ->signedRoute('sistema.gerador.index')
                ->with('success', 'Gerador cadastrado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar gerador: ' . $e->getMessage());
        }
    }

    /**
     * Prepara os dados para o GeradorService
     */
    private function prepararDadosParaGerador(GeradorCadastros $gerador): array
    {
        // Carregar os campos com relacionamentos
        $gerador->load(['campos.tipo', 'menu']);

        $data = [
            'classe' => $gerador->classe,
            'modulo_pai' => $gerador?->menu ? $gerador?->menu?->descricao : 'Sistema',
            'criar_permissoes' => $gerador->criar_permissoes,
            'criar_menu' => $gerador->criar_menu,
            'soft_delete' => $gerador->soft_delete,
            'campos' => []
        ];

        foreach ($gerador->campos as $campo) {
            $data['campos'][] = [
                'nome' => $campo->nome,
                'tipo' => $campo->tipo->descricao ?? $campo->tipo_id,
                'max' => $campo->tamanho_maximo,
                'obrigatorio' => $campo->obrigatorio,
                'unique' => $campo->unico,
                'relacionamento' => $campo->relacionamento
            ];
        }

        return $data;
    }

    /**
     * Display the specified resource.
     */
    public function show(GeradorCadastros $gerador)
    {
        $arTabelas = $this->retornaTabelasSistema();
        $menusPai = $this->retornaMenusPai();
        $tiposCampos = PadraoTipo::where('padrao_id', '=', Padrao::TipoCamposGerador)->get();

        $bloquearCampos = true;

        // Carregar o gerador com os campos e o relacionamento tipo
        $gerador->load(['campos.tipo', 'menu']);

        return view('sistema.gerador.show', compact('gerador', 'arTabelas', 'menusPai', 'tiposCampos', 'bloquearCampos'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GeradorCadastros $gerador)
    {
        $arTabelas = $this->retornaTabelasSistema();
        $menusPai = $this->retornaMenusPai();
        $tiposCampos = PadraoTipo::where('padrao_id', '=', Padrao::TipoCamposGerador)->get();

        // Passar campos antigos se houver erro de validação
        $camposAntigos = old('campos', []);

        return view('sistema.gerador.edit', compact('gerador', 'menusPai', 'arTabelas', 'tiposCampos', 'camposAntigos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, GeradorCadastros $gerador)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            // Atualizar o gerador
            $gerador->update([
                'classe' => $validated['classe'],
                'menuPai_id' => $validated['menuPai_id'] ?? null,
                'criar_permissoes' => $validated['criar_permissoes'] ?? false,
                'criar_menu' => $validated['criar_menu'] ?? false,
                'soft_delete' => $validated['soft_delete'] ?? false,
            ]);

            // Remover campos existentes
            $gerador->campos()->delete();

            // Salvar os campos dinâmicos
            if (isset($validated['campos']) && is_array($validated['campos'])) {
                foreach ($validated['campos'] as $campoData) {
                    if (!empty($campoData['nome']) && !empty($campoData['tipo'])) {
                        GeradorCadastroCampo::create([
                            'gerador_id' => $gerador->id,
                            'nome' => $campoData['nome'],
                            'tipo_id' => $campoData['tipo'],
                            'tamanho_maximo' => $campoData['max'] ?? null,
                            'relacionamento' => $campoData['relacionamento'] ?? null,
                            'obrigatorio' => $campoData['obrigatorio'] ?? false,
                            'unico' => $campoData['unique'] ?? false,
                        ]);
                    }
                }
            }

            DB::commit();

            // Chamar o GeradorService após o commit
            $geradorService = new GeradorService();
            $geradorService->gerarCadastro($this->prepararDadosParaGerador($gerador));

            return redirect()
                ->signedRoute('sistema.gerador.index')
                ->with('success', 'Gerador atualizado com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar gerador: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GeradorCadastros $gerador)
    {
        try {
            DB::beginTransaction();

            // Remover campos primeiro (devido à foreign key)
            $gerador->campos()->delete();

            // Remover o gerador
            $gerador->delete();

            DB::commit();

            return redirect()
                ->signedRoute('sistema.gerador.index')
                ->with('success', 'Gerador excluído com sucesso!');

        } catch (\Exception $e) {
            DB::rollback();

            return redirect()
                ->signedRoute('sistema.gerador.index')
                ->with('error', 'Erro ao excluir gerador: ' . $e->getMessage());
        }
    }

    /**
     * Retorna as tabelas do sistema para o gerador
     */
    private function retornaTabelasSistema()
    {
        $tables = DB::select('SHOW TABLES');
        $tableNames = [];
        foreach ($tables as $table) {
            $tableName = array_values((array)$table)[0];
            if (!in_array($tableName, ['migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens'])) {
                $tableNames[] = $tableName;
            }
        }
        return $tableNames;
    }

    /**
     * Retorna os menus pai disponíveis
     */
    private function retornaMenusPai()
    {
        $menusPrincipais = DB::table('menu')
            ->whereNull('menuPai_id')
            ->whereNotNull('descricao')
            ->orderBy('descricao')
            ->get();


        return $menusPrincipais;
    }
}
