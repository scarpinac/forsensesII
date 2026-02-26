<?php

namespace App\Services;

use App\Models\Sistema\Menu;
use App\Services\Generators\ControllerGenerator;
use App\Services\Generators\MigrationGenerator;
use App\Services\Generators\ModelGenerator;
use App\Services\Generators\ObserverGenerator;
use App\Services\Generators\RequestGenerator;
use App\Services\Generators\ViewGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GeradorServiceRefactored
{
    private array $data;
    private string $classe;
    private string $moduloPai;
    private string $tabela;
    private string $namespace;

    public function gerarCadastro(array $data): array
    {
        Log::info('GeradorServiceRefactored::gerarCadastro iniciado com dados:', $data);

        $this->data = $data;
        $this->classe = $data['classe'];
        $this->moduloPai = $data['modulo_pai'];
        $this->namespace = 'App\\Http\\Controllers\\' . $this->moduloPai;

        $resultado = [
            'files' => [],
            'permissoes' => [],
            'menu' => null
        ];

        Log::info('Iniciando geração de arquivos...');

        // Gerar arquivos usando os geradores especializados
        $resultado['files'] = array_merge(
            $resultado['files'],
            $this->generateMigrations(),
            $this->generateModels(),
            $this->generateController(),
            $this->generateRequests(),
            $this->generateViews(),
            $this->generateObserver()
        );

        Log::info('Arquivos gerados:', $resultado['files']);

        // Gerar permissões
        if ($data['criar_permissoes']) {
            $resultado['permissoes'] = $this->generatePermissions();
        }

        // Gerar menu
        if ($data['criar_menu']) {
            $resultado['menu'] = $this->generateMenu();
        }

        return $resultado;
    }

    private function generateMigrations(): array
    {
        $generator = new MigrationGenerator($this->classe, $this->data);
        return $generator->generate();
    }

    private function generateModels(): array
    {
        $generator = new ModelGenerator($this->classe, $this->data);
        return $generator->generate();
    }

    private function generateController(): array
    {
        $generator = new ControllerGenerator($this->classe, $this->moduloPai, $this->data);
        return [$generator->generate()];
    }

    private function generateRequests(): array
    {
        $generator = new RequestGenerator($this->classe, $this->moduloPai, $this->data);
        return $generator->generate();
    }

    private function generateViews(): array
    {
        $generator = new ViewGenerator($this->classe, $this->moduloPai, $this->data);
        return $generator->generate();
    }

    private function generateObserver(): array
    {
        $generator = new ObserverGenerator($this->classe, $this->moduloPai);
        return [$generator->generate()];
    }

    private function generatePermissions(): array
    {
        $permissoes = [];
        $actions = ['index', 'create', 'edit', 'show', 'destroy', 'history'];

        foreach ($actions as $action) {
            $permissao = strtolower($this->moduloPai) . '.' . strtolower($this->classe) . '.' . $action;

            DB::table('permissao')->insert([
                'descricao' => $permissao,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $permissoes[] = $permissao;
        }

        return $permissoes;
    }

    private function generateMenu(): array
    {
        $permissaoIndex = strtolower($this->moduloPai) . '.' . strtolower($this->classe) . '.index';
        $permissao = DB::table('permissao')->where('descricao', $permissaoIndex)->first();

        if (!$permissao) {
            return [];
        }

        $menuPai = Menu::where('descricao', '=', $this->moduloPai)->first();

        $menuData = [
            'descricao' => $this->classe,
            'icone' => 'fas fa-database',
            'rota' => $permissaoIndex,
            'menuPai_id' => $menuPai ? $menuPai->id : null,
            'permissao_id' => $permissao->id,
            'situacao_id' => 1, // Ativo
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('menu')->insert($menuData);

        return $menuData;
    }
}
