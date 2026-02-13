<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Services\GeradorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GeradorCadastrosController extends Controller
{
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.gerador.index'), 403, 'Acesso não autorizado');

        return view('sistema.gerador.index');
    }

    public function generate(Request $request)
    {
        abort_if (!Auth::user()->canAccess('sistema.gerador.generate'), 403, 'Acesso não autorizado');

        $data = $request->validate([
            'classe' => 'required|string|max:50',
            'modulo_pai' => 'required|string|max:50',
            'criar_permissoes' => 'boolean',
            'criar_menu' => 'boolean',
            'campos' => 'required|array|min:1',
            'campos.*.nome' => 'required|string|max:50',
            'campos.*.tipo' => 'required|in:string,integer,double,date,boolean,text,file,select',
            'campos.*.obrigatorio' => 'boolean',
            'campos.*.max' => 'nullable|integer|min:1',
            'campos.*.relacionamento' => 'nullable|string|max:50',
            'campos.*.unique' => 'boolean',
            'soft_delete' => 'boolean',
            'timestamps' => 'boolean',
        ]);

        // Garantir que todos os campos necessários existam em cada campo
        if (isset($data['campos'])) {
            foreach ($data['campos'] as $index => $campo) {
                $data['campos'][$index] = array_merge([
                    'obrigatorio' => false,
                    'max' => null,
                    'relacionamento' => null,
                    'unique' => false,
                ], $campo);
            }
        }

        try {
            $gerador = new GeradorService();
            $resultado = $gerador->gerarCadastro($data);

            return response()->json([
                'success' => true,
                'message' => 'Cadastro gerado com sucesso!',
                'files' => $resultado['files'],
                'permissoes' => $resultado['permissoes'] ?? [],
                'menu' => $resultado['menu'] ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao gerar cadastro: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getModulosDisponiveis()
    {
        $modulos = [];

        // Buscar menus principais da tabela menu (que não têm menu_pai)
        try {
            $menusPrincipais = DB::table('menu')
                ->whereNull('menuPai_id')
                ->whereNotNull('descricao')
                ->orderBy('descricao')
                ->get();

            foreach ($menusPrincipais as $menu) {
                // Extrair o nome do módulo da descrição do menu
                $descricao = trim($menu->descricao);

                // Converter para PascalCase de forma mais inteligente
                $modulo = $descricao;

                // Remover espaços e underscores, mantendo capitalização
                $modulo = str_replace([' ', '_'], '', $modulo);

                // Se estiver em minúsculas, converter para PascalCase
                if (strtolower($modulo) === $modulo) {
                    $modulo = ucwords(strtolower($modulo));
                }

                // Casos especiais comuns
                $moduloMap = [
                    'Sistema' => 'Sistema',
                    'Cadastro' => 'Cadastro',
                    'Relatorio' => 'Relatorio',
                    'Financeiro' => 'Financeiro',
                    'Estoque' => 'Estoque',
                    'Relatórios' => 'Relatorio',
                    'Cadastros' => 'Cadastro',
                ];

                if (isset($moduloMap[$modulo])) {
                    $modulo = $moduloMap[$modulo];
                }

                // Evitar duplicatas
                if (!in_array($modulo, $modulos) && !empty($modulo)) {
                    $modulos[] = $modulo;
                }
            }
        } catch (\Exception $e) {
            // Se falhar, usa o método fallback
        }

        // Fallback: Adicionar módulos comuns se não encontrou nada
        if (empty($modulos)) {
            $modulosComuns = ['Sistema', 'Cadastro', 'Relatorio', 'Financeiro', 'Estoque'];
            foreach ($modulosComuns as $modulo) {
                $modulos[] = $modulo;
            }
        }

        // Ordenar alfabeticamente
        sort($modulos);

        return response()->json($modulos);
    }

    public function getTabelasDisponiveis()
    {
        try {
            $tables = \DB::select('SHOW TABLES');
            $tableNames = [];
            foreach ($tables as $table) {
                $tableName = array_values((array)$table)[0];
                if (!in_array($tableName, ['migrations', 'password_resets', 'failed_jobs', 'personal_access_tokens'])) {
                    $tableNames[] = $tableName;
                }
            }
            return response()->json($tableNames);
        } catch (\Exception $e) {
            return response()->json([]);
        }
    }
}
