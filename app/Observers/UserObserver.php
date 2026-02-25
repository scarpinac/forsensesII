<?php

namespace App\Observers;

use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        // Limpar cache de permissões se perfis foram alterados
        if ($user->wasChanged(['admin']) || $user->perfis()->isDirty()) {
            $user->clearPermissionCache();
            Log::info("Cache de permissões limpo após atualização do usuário {$user->id}");
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $user->clearPermissionCache();
        Log::info("Cache de permissões limpo após exclusão do usuário {$user->id}");
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        $user->clearPermissionCache();
        Log::info("Cache de permissões limpo após restauração do usuário {$user->id}");
    }
}
