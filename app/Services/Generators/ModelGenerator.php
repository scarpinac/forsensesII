<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ModelGenerator
{
    private string $classe;
    private string $tabela;
    private array $data;

    public function __construct(string $classe, array $data)
    {
        $this->classe = $classe;
        $this->tabela = Str::snake($classe);
        $this->data = $data;
    }

    public function generate(): array
    {
        $files = [];
        
        // Model principal
        $files[] = $this->generateMainModel();
        
        // Model de histórico
        $files[] = $this->generateHistoryModel();
        
        return $files;
    }

    private function generateMainModel(): string
    {
        $path = app_path("Models/{$this->classe}.php");

        $fillable = [];
        $casts = [];
        $relationships = [];

        foreach ($this->data['campos'] as $campo) {
            $fillable[] = "'{$campo['nome']}'";

            $this->addCasts($campo, $casts);
            
            if ($campo['relacionamento']) {
                $relationships[] = $this->generateRelationship($campo);
            }
        }

        $content = $this->getMainModelTemplate(
            $this->classe,
            $this->tabela,
            $fillable,
            $casts,
            $relationships,
            $this->data
        );

        File::put($path, $content);
        return "Model: app/Models/{$this->classe}.php";
    }

    private function generateHistoryModel(): string
    {
        $historyModelPath = app_path("Models/{$this->classe}Historico.php");
        $historyContent = $this->getHistoryModelTemplate($this->classe, $this->tabela);
        File::put($historyModelPath, $historyContent);

        return "Model Histórico: app/Models/{$this->classe}Historico.php";
    }

    private function addCasts(array $campo, array &$casts): void
    {
        switch ($campo['tipo']) {
            case 'boolean':
                $casts[] = "'{$campo['nome']}' => 'boolean'";
                break;
            case 'date':
                $casts[] = "'{$campo['nome']}' => 'date'";
                break;
            case 'double':
                $casts[] = "'{$campo['nome']}' => 'decimal:2'";
                break;
        }
    }

    private function generateRelationship(array $campo): string
    {
        $relacionamento = $campo['relacionamento'] ?? null;

        if (!$relacionamento || empty($relacionamento)) return '';

        $nomeRelacionamento = $campo['nome'];
        $classeRelacionamento = $relacionamento;

        $relationship = "    public function " . Str::camel(Str::plural($nomeRelacionamento)) . "(): BelongsTo\n";
        $relationship .= "    {\n";
        $relationship .= "        return \$this->belongsTo({$classeRelacionamento}::class, '{$nomeRelacionamento}');\n";
        $relationship .= "    }\n\n";

        return $relationship;
    }

    private function getMainModelTemplate(string $classe, string $tabela, array $fillable, array $casts, array $relationships, array $data): string
    {
        $softDeletes = $data['soft_delete'] ? 'use Illuminate\\Database\\Eloquent\\SoftDeletes;' : '';
        $softDeletesTrait = $data['soft_delete'] ? 'SoftDeletes' : '';

        $fillableStr = implode(', ', $fillable);
        $castsStr = !empty($casts) ? "\n    protected \$casts = [\n        " . implode(",\n        ", $casts) . "\n    ];" : '';
        $relationshipsStr = !empty($relationships) ? "\n" . implode('', $relationships) : '';

        return "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\n{$softDeletes}\n\nclass {$classe} extends Model\n{\n    use HasFactory, {$softDeletesTrait};\n\n    protected \$table = '{$tabela}';\n\n    protected \$fillable = [\n        {$fillableStr}\n    ];{$castsStr}{$relationshipsStr}\n}";
    }

    private function getHistoryModelTemplate(string $classe, string $tabela): string
    {
        return "<?php\n\nnamespace App\\Models;\n\nuse Illuminate\\Database\\Eloquent\\Factories\\HasFactory;\nuse Illuminate\\Database\\Eloquent\\Model;\nuse Illuminate\\Database\\Eloquent\\Relations\\BelongsTo;\nuse Illuminate\\Database\\Eloquent\\SoftDeletes;\n\nclass {$classe}Historico extends Model\n{\n    use HasFactory, SoftDeletes;\n\n    protected \$table = '{$tabela}_historico';\n\n    protected \$fillable = [\n        'user_id',\n        '{$tabela}_id',\n        'dados_anteriores',\n        'dados_novos',\n        'tipoAlteracao_id'\n    ];\n\n    protected \$casts = [\n        'dados_anteriores' => 'array',\n        'dados_novos' => 'array'\n    ];\n\n    public function user(): BelongsTo\n    {\n        return \$this->belongsTo(User::class);\n    }\n\n    public function {$tabela}(): BelongsTo\n    {\n        return \$this->belongsTo({$classe}::class);\n    }\n\n    public function tipoAlteracao(): BelongsTo\n    {\n        return \$this->belongsTo(PadraoTipo::class);\n    }\n}";
    }
}
