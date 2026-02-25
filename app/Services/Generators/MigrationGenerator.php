<?php

namespace App\Services\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MigrationGenerator
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
        
        // Migration principal
        $files[] = $this->generateMainMigration();
        
        // Migration de histórico
        $files[] = $this->generateHistoryMigration();
        
        return $files;
    }

    private function generateMainMigration(): string
    {
        $timestamp = date('Y_m_d_His');
        $filename = "{$timestamp}_create_{$this->tabela}_table.php";
        $path = database_path("migrations/{$filename}");

        $campos = '';
        foreach ($this->data['campos'] as $campo) {
            $campos .= $this->generateFieldMigration($campo);
        }

        $content = $this->getMainMigrationTemplate($this->tabela, $campos, $this->data);

        File::put($path, $content);
        return "Migration: {$filename}";
    }

    private function generateHistoryMigration(): string
    {
        $timestamp = date('Y_m_d_His', strtotime('+1 second'));
        $tabelaHistorico = $this->tabela . '_historico';
        $filename = "{$timestamp}_create_{$tabelaHistorico}_table.php";
        $path = database_path("migrations/{$filename}");

        $content = $this->getHistoryMigrationTemplate($tabelaHistorico, $this->tabela);

        File::put($path, $content);
        return "Migration Histórico: {$filename}";
    }

    private function generateFieldMigration(array $campo): string
    {
        $nome = $campo['nome'];
        $tipo = $campo['tipo'];
        $obrigatorio = $campo['obrigatorio'] ?? false;
        $max = $campo['max'] ?? null;
        $unique = $campo['unique'] ?? false;
        $relacionamento = $campo['relacionamento'] ?? null;

        $line = "            \$table->";

        switch ($tipo) {
            case 'String':
                $line .= $max ? "string('{$nome}', {$max})" : "string('{$nome}')";
                break;
            case 'Inteiro':
                $line .= "integer('{$nome}')";
                break;
            case 'Decimal/Valor':
                $line .= "decimal('{$nome}', 10, 2)";
                break;
            case 'Data':
                $line .= "date('{$nome}')";
                break;
            case 'Booleano':
                $line .= "boolean('{$nome}')";
                break;
            case 'Texto Longo':
                $line .= "text('{$nome}')";
                break;
            case 'Arquivo':
                $line .= "string('{$nome}')";
                break;
            case 'Select/Relacionamento':
                $line .= "foreignId('{$nome}')->nullable()";
                if ($relacionamento && !empty($relacionamento)) {
                    $tabelaRelacionamento = Str::snake(Str::plural($relacionamento));
                    $line .= "->constrained('{$tabelaRelacionamento}')";
                }
                break;
        }

        if ($obrigatorio && $tipo !== 'Select/Relacionamento') {
            $line = str_replace(')', ')->notNullable()', $line);
        }

        if ($unique) {
            $line .= "->unique()";
        }

        $line .= ";\n";
        return $line;
    }

    private function getMainMigrationTemplate(string $tabela, string $campos, array $data): string
    {
        $timestamps = "\n            \$table->timestamps();";
        $softDeletes = $data['soft_delete'] ? "\n            \$table->softDeletes();" : "";

        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        Schema::create('{$tabela}', function (Blueprint \$table) {\n            \$table->id();{$campos}{$timestamps}{$softDeletes}\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tabela}');\n    }\n};";
    }

    private function getHistoryMigrationTemplate(string $tabelaHistorico, string $tabelaPrincipal): string
    {
        return "<?php\n\nuse Illuminate\\Database\\Migrations\\Migration;\nuse Illuminate\\Database\\Schema\\Blueprint;\nuse Illuminate\\Support\\Facades\\Schema;\n\nreturn new class extends Migration\n{\n    /**\n     * Run the migrations.\n     */\n    public function up(): void\n    {\n        Schema::create('{$tabelaHistorico}', function (Blueprint \$table) {\n            \$table->id();\n            \$table->foreignId('user_id')->constrained('users');\n            \$table->foreignId('{$tabelaPrincipal}_id')->constrained('{$tabelaPrincipal}');\n            \$table->text('dados_anteriores')->nullable();\n            \$table->text('dados_novos')->nullable();\n            \$table->foreignId('tipoAlteracao_id')->constrained('padrao_tipo');\n            \$table->timestamps();\n            \$table->softDeletes();\n        });\n    }\n\n    /**\n     * Reverse the migrations.\n     */\n    public function down(): void\n    {\n        Schema::dropIfExists('{$tabelaHistorico}');\n    }\n};";
    }
}
