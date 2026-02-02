<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledNotifications;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledNotificationsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Processa notificações agendadas que devem ser enviadas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando processamento de notificações agendadas...');
        
        try {
            // Disparar o job para processar as notificações
            ProcessScheduledNotifications::dispatch();
            
            $this->info('✅ Job de processamento disparado com sucesso!');
            $this->info('📝 Verifique os logs para acompanhar o processamento');
            
            Log::info('Command notifications:process-scheduled executado manualmente');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Erro ao processar notificações agendadas: ' . $e->getMessage());
            Log::error('Erro no command notifications:process-scheduled: ' . $e->getMessage());
            
            return Command::FAILURE;
        }
    }
}
