<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter usuários para exemplos
        $users = User::all();
        $adminUsers = User::whereHas('perfis', function ($query) {
            $query->where('descricao', 'admin');
        })->get();

        // Notificação para todos os usuários
        Notification::create([
            'title' => 'Bem-vindo ao Sistema!',
            'message' => 'Olá! Seja bem-vindo ao nosso sistema de gestão. Explore todas as funcionalidades disponíveis.',
            'type' => 'info',
            'target_type' => 'all',
            'icon' => 'fas fa-hand-wave',
            'url' => '/dashboard',
            'is_active' => true,
        ]);

        // Notificação de sucesso para todos
        Notification::create([
            'title' => 'Sistema Atualizado',
            'message' => 'O sistema foi atualizado com novas funcionalidades de notificações. Confira as novidades!',
            'type' => 'success',
            'target_type' => 'all',
            'icon' => 'fas fa-rocket',
            'url' => '/sistema/notification',
            'is_active' => true,
        ]);

        // Notificação de aviso para administradores
        if ($adminUsers->isNotEmpty()) {
            Notification::create([
                'title' => 'Backup Agendado',
                'message' => 'O backup automático do sistema será executado hoje à noite. Verifique se tudo está em ordem.',
                'type' => 'warning',
                'target_type' => 'role',
                'target_roles' => ['admin'],
                'icon' => 'fas fa-database',
                'url' => '/sistema/backup',
                'is_active' => true,
            ]);
        }

        // Notificações específicas para alguns usuários
        if ($users->count() >= 3) {
            $specificUsers = $users->take(3)->pluck('id')->toArray();
            
            Notification::create([
                'title' => 'Tarefa Pendente',
                'message' => 'Você possui tarefas pendentes que precisam ser revisadas. Acesse o painel para mais detalhes.',
                'type' => 'warning',
                'target_type' => 'specific',
                'target_users' => $specificUsers,
                'icon' => 'fas fa-tasks',
                'url' => '/sistema/tasks',
                'is_active' => true,
            ]);
        }

        // Notificação de erro (exemplo)
        Notification::create([
            'title' => 'Falha na Importação',
            'message' => 'Ocorreu uma falha ao importar os dados. Verifique o formato do arquivo e tente novamente.',
            'type' => 'error',
            'target_type' => 'role',
            'target_roles' => ['admin'],
            'icon' => 'fas fa-exclamation-triangle',
            'url' => '/sistema/import',
            'is_active' => true,
        ]);

        // Notificação de sistema
        Notification::create([
            'title' => 'Manutenção Programada',
            'message' => 'O sistema passará por manutenção programada neste sábado das 02:00 às 04:00. Durante este período, o sistema estará indisponível.',
            'type' => 'system',
            'target_type' => 'all',
            'icon' => 'fas fa-tools',
            'url' => null,
            'is_active' => true,
            'expires_at' => now()->addDays(7),
        ]);

        // Notificação informativa com expiração
        Notification::create([
            'title' => 'Novo Recurso Disponível',
            'message' => 'Novo módulo de relatórios está disponível! Gere relatórios personalizados com apenas alguns cliques.',
            'type' => 'info',
            'target_type' => 'all',
            'icon' => 'fas fa-chart-bar',
            'url' => '/sistema/reports',
            'is_active' => true,
            'expires_at' => now()->addDays(30),
        ]);

        // Notificação de sucesso para perfis específicos
        Notification::create([
            'title' => 'Meta Atingida!',
            'message' => 'Parabéns! A equipe atingiu a meta mensal. Continue com o excelente trabalho!',
            'type' => 'success',
            'target_type' => 'role',
            'target_roles' => ['admin', 'manager'],
            'icon' => 'fas fa-trophy',
            'url' => '/sistema/dashboard',
            'is_active' => true,
        ]);

        // Notificação informativa para usuários específicos
        if ($users->count() >= 5) {
            $newUsers = $users->take(5)->pluck('id')->toArray();
            
            Notification::create([
                'title' => 'Tutorial Disponível',
                'message' => 'Acessamos que você é novo no sistema. Confira nosso tutorial interativo para aprender a usar todas as funcionalidades.',
                'type' => 'info',
                'target_type' => 'specific',
                'target_users' => $newUsers,
                'icon' => 'fas fa-graduation-cap',
                'url' => '/tutorial',
                'is_active' => true,
                'expires_at' => now()->addDays(14),
            ]);
        }

        // Notificação de aviso geral
        Notification::create([
            'title' => 'Atualização de Senha',
            'message' => 'Lembre-se de atualizar sua senha regularmente para manter a segurança da sua conta.',
            'type' => 'warning',
            'target_type' => 'all',
            'icon' => 'fas fa-key',
            'url' => '/profile',
            'is_active' => true,
        ]);

        $this->command->info('Notificações de exemplo criadas com sucesso!');
    }
}
