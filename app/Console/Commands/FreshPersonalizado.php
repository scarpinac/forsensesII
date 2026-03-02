<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class FreshPersonalizado extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fresh:personalizado 
                            {--force : Força a execução sem confirmação}
                            {--migration=2026_02_17_143000_add_performance_indexes.php : Migration limite para primeira etapa}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fresh personalizado: limpa banco, roda migrations até ponto específico, executa seeders, depois continua migrations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $migrationLimit = $this->option('migration');
        
        $this->info('🚀 Iniciando processo de Fresh Personalizado...');
        $this->newLine();

        // Confirmar execução
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  ATENÇÃO: Isso irá limpar completamente o banco de dados. Deseja continuar?')) {
                $this->info('❌ Operação cancelada pelo usuário.');
                return Command::FAILURE;
            }
        }

        try {
            // Passo 1: Limpar banco
            $this->step(1, 6, 'Limpando banco de dados completamente...');
            $this->call('db:wipe', ['--force' => true]);
            $this->info('✅ Banco de dados limpo com sucesso!');

            // Passo 2: Rodar migrations até o limite
            $this->step(2, 6, "Rodando migrations iniciais (até {$migrationLimit})...");
            
            // Obter lista de migrations até o limite
            $migrations = $this->getMigrationsUpTo($migrationLimit);
            
            if (empty($migrations)) {
                $this->warn("Nenhuma migration encontrada até {$migrationLimit}");
            } else {
                foreach ($migrations as $migration) {
                    $this->line("   Executando: {$migration}");
                    
                    // Usar Artisan::call para cada migration individualmente
                    $exitCode = Artisan::call('migrate', [
                        '--path' => "database/migrations/{$migration}",
                        '--force' => true
                    ]);
                    
                    if ($exitCode !== 0) {
                        $output = Artisan::output();
                        throw new \Exception("Falha ao executar migration {$migration}: {$output}");
                    }
                }
            }
            $this->info('✅ Migrations iniciais executadas com sucesso!');

            // Passo 3: Rodar seeders
            $this->step(3, 6, 'Executando seeders...');
            $this->call('db:seed', ['--force' => true]);
            $this->info('✅ Seeders executados com sucesso!');

            // Passo 4: Continuar migrations restantes
            $this->step(4, 6, 'Rodando migrations restantes...');
            $this->call('migrate', ['--force' => true]);
            $this->info('✅ Migrations restantes executadas com sucesso!');

            // Passo 5: Otimizar cache
            $this->step(5, 6, 'Otimizando cache da aplicação...');
            $this->call('config:cache');
            $this->call('route:cache');
            $this->call('view:cache');
            $this->info('✅ Cache otimizado com sucesso!');

            // Passo 6: Verificar status
            $this->step(6, 6, 'Verificando status final...');
            $this->call('migrate:status');

            $this->newLine();
            $this->info('🎉 Processo de Fresh Personalizado concluído com sucesso!');
            $this->info('🚀 Banco de dados pronto para uso!');

            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->newLine();
            $this->error('❌ Erro durante a execução do Fresh Personalizado:');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Exibe step formatado
     */
    private function step(int $current, int $total, string $description): void
    {
        $this->line("📍 [{$current}/{$total}] - {$description}");
    }

    /**
     * Obtém lista de migrations até o limite especificado
     */
    private function getMigrationsUpTo(string $limitMigration): array
    {
        $migrationsPath = database_path('migrations');
        $allMigrations = glob($migrationsPath . '/*.php');
        $result = [];

        foreach ($allMigrations as $migration) {
            $filename = basename($migration);
            
            // Compara filenames para determinar ordem
            if ($filename <= $limitMigration) {
                $result[] = $filename;
            }
        }

        sort($result);
        return $result;
    }
}
