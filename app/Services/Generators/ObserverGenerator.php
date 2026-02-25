<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;

class ObserverGenerator
{
    private string $classe;
    private string $moduloPai;

    public function __construct(string $classe, string $moduloPai)
    {
        $this->classe = $classe;
        $this->moduloPai = $moduloPai;
    }

    public function generate(): string
    {
        $path = app_path("Observers/{$this->classe}Observer.php");
        $historicoModel = $this->classe . 'Historico';

        $content = $this->getObserverTemplate($this->classe, $historicoModel);

        File::put($path, $content);
        return "Observer: app/Observers/{$this->classe}Observer.php";
    }

    private function getObserverTemplate(string $classe, string $historicoModel): string
    {
        return "<?php\n\nnamespace App\\Observers;\n\nuse App\\Models\\{$classe};\nuse App\\Models\\{$historicoModel};\nuse Illuminate\\Support\\Facades\\Auth;\nuse Illuminate\\Support\\Facades\\Log;\n\nclass {$classe}Observer\n{\n    /**\n     * Handle the {$classe} \"created\" event.\n     */\n    public function created({$classe} \${$classeLower}): void\n    {\n        \$this->registrarHistorico(\${$classeLower}, 'create');\n        Log::info('{$classe} criado', ['id' => \${$classeLower}->id, 'user' => Auth::id()]);\n    }\n\n    /**\n     * Handle the {$classe} \"updated\" event.\n     */\n    public function updated({$classe} \${$classeLower}): void\n    {\n        \$this->registrarHistorico(\${$classeLower}, 'update');\n        Log::info('{$classe} atualizado', ['id' => \${$classeLower}->id, 'user' => Auth::id()]);\n    }\n\n    /**\n     * Handle the {$classe} \"deleted\" event.\n     */\n    public function deleted({$classe} \${$classeLower}): void\n    {\n        \$this->registrarHistorico(\${$classeLower}, 'delete');\n        Log::info('{$classe} excluído', ['id' => \${$classeLower}->id, 'user' => Auth::id()]);\n    }\n\n    /**\n     * Handle the {$classe} \"restored\" event.\n     */\n    public function restored({$classe} \${$classeLower}): void\n    {\n        \$this->registrarHistorico(\${$classeLower}, 'restore');\n        Log::info('{$classe} restaurado', ['id' => \${$classeLower}->id, 'user' => Auth::id()]);\n    }\n\n    /**\n     * Registrar histórico de alterações\n     */\n    private function registrarHistorico({$classe} \${$classeLower}, string \$acao): void\n    {\n        \$dadosAnteriores = null;\n        \$dadosNovos = null;\n\n        if (\$acao === 'update') {\n            \$dadosAnteriores = \${$classeLower}->getOriginal();\n            \$dadosNovos = \${$classeLower}->getChanges();\n        } elseif (\$acao === 'create') {\n            \$dadosNovos = \${$classeLower}->getAttributes();\n        }\n\n        // Obter ID do tipo de alteração baseado na ação\n        \$tipoAlteracaoId = \$this->getTipoAlteracaoId(\$acao);\n\n        {$historicoModel}::create([\n            'user_id' => Auth::id(),\n            '" . strtolower($classe) . "_id' => \${$classeLower}->id,\n            'dados_anteriores' => \$dadosAnteriores ? json_encode(\$dadosAnteriores) : null,\n            'dados_novos' => \$dadosNovos ? json_encode(\$dadosNovos) : null,\n            'tipoAlteracao_id' => \$tipoAlteracaoId,\n        ]);\n    }\n\n    /**\n     * Obter ID do tipo de alteração\n     */\n    private function getTipoAlteracaoId(string \$acao): int\n    {\n        // Mapear ações para IDs (isso deve ser ajustado conforme seu sistema)\n        \$mapeamento = [\n            'create' => 1, // Inserção\n            'update' => 2, // Atualização\n            'delete' => 3, // Exclusão\n            'restore' => 4, // Restauração\n        ];\n\n        return \$mapeamento[\$acao] ?? 2; // Default para atualização\n    }\n}";
    }
}
