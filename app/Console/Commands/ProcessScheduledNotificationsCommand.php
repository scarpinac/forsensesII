<?php

namespace App\Console\Commands;

use App\Jobs\ProcessScheduledNotifications;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as CommandAlias;

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
            ProcessScheduledNotifications::dispatch()->onQueue('notificacao');
            return CommandAlias::SUCCESS;

        } catch (\Exception $e) {
            $this->error('❌ Erro ao processar notificações agendadas: ' . $e->getMessage());
            Log::error('Erro no command notifications:process-scheduled: ' . $e->getMessage());

            return CommandAlias::FAILURE;
        }
    }
}
