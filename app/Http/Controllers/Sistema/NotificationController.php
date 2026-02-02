<?php

namespace App\Http\Controllers\Sistema;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\NotificationRead;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.index'), 403, 'Acesso não autorizado');

        $notifications = Notification::withCount(['reads' => function ($query) {
            $query->where('is_read', true);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('sistema.notification.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.create'), 403, 'Acesso não autorizado');

        $users = User::orderBy('name')->get();
        $roles = DB::table('roles')->orderBy('name')->get();

        return view('sistema.notification.create', compact('users', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.create'), 403, 'Acesso não autorizado');

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error,system',
            'target_type' => 'required|in:all,specific,role',
            'target_users' => 'required_if:target_type,specific|array',
            'target_users.*' => 'exists:users,id',
            'target_roles' => 'required_if:target_type,role|array',
            'target_roles.*' => 'exists:roles,name',
            'icon' => 'nullable|string|max:50',
            'url' => 'nullable|url',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $notification = Notification::create($request->all());

        // Criar registros de leitura para todos os usuários alvo
        $this->createNotificationReads($notification);

        return redirect()
            ->route('sistema.notification.index')
            ->with('success', 'Notificação criada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Notification $notification)
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.show'), 403, 'Acesso não autorizado');

        $notification->load(['reads.user', 'reads' => function ($query) {
            $query->orderBy('read_at', 'desc');
        }]);

        $readStats = [
            'total' => $notification->reads->count(),
            'read' => $notification->reads->where('is_read', true)->count(),
            'unread' => $notification->reads->where('is_read', false)->count(),
        ];

        return view('sistema.notification.show', compact('notification', 'readStats'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Notification $notification)
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.edit'), 403, 'Acesso não autorizado');

        $users = User::orderBy('name')->get();
        $roles = DB::table('roles')->orderBy('name')->get();

        return view('sistema.notification.edit', compact('notification', 'users', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Notification $notification)
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.edit'), 403, 'Acesso não autorizado');

        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,success,warning,error,system',
            'target_type' => 'required|in:all,specific,role',
            'target_users' => 'required_if:target_type,specific|array',
            'target_users.*' => 'exists:users,id',
            'target_roles' => 'required_if:target_type,role|array',
            'target_roles.*' => 'exists:roles,name',
            'icon' => 'nullable|string|max:50',
            'url' => 'nullable|url',
            'expires_at' => 'nullable|date|after:now',
        ]);

        $notification->update($request->all());

        // Recriar registros de leitura se os destinatários mudaram
        $this->createNotificationReads($notification);

        return redirect()
            ->route('sistema.notification.index')
            ->with('success', 'Notificação atualizada com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Notification $notification)
    {
        abort_if (!Auth::user()->canAccess('sistema.notification.destroy'), 403, 'Acesso não autorizado');

        $notification->delete();

        return redirect()
            ->route('sistema.notification.index')
            ->with('success', 'Notificação excluída com sucesso!');
    }

    /**
     * API: Obter notificações do usuário logado
     */
    public function getUserNotifications()
    {
        $user = Auth::user();
        
        $notifications = Notification::active()
            ->notExpired()
            ->where(function ($query) use ($user) {
                $query->where('target_type', 'all')
                    ->orWhere('target_type', 'specific')
                    ->whereJsonContains('target_users', $user->id)
                    ->orWhere('target_type', 'role')
                    ->whereJsonContains('target_roles', $user->perfis()->pluck('descricao'));
            })
            ->with(['reads' => function ($query) use ($user) {
                $query->where('user_id', $user->id);
            }])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Formatar para o frontend
        $formattedNotifications = $notifications->map(function ($notification) use ($user) {
            $read = $notification->reads->first();
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'icon' => $notification->icon,
                'url' => $notification->url,
                'created_at' => $notification->created_at->diffForHumans(),
                'is_read' => $read ? $read->is_read : false,
            ];
        });

        return response()->json([
            'notifications' => $formattedNotifications,
            'unread_count' => $formattedNotifications->where('is_read', false)->count(),
        ]);
    }

    /**
     * API: Marcar notificação como lida
     */
    public function markAsRead(Request $request)
    {
        \Log::info('markAsRead chamado', [
            'notification_id' => $request->notification_id,
            'user_authenticated' => Auth::check(),
            'user_id' => Auth::id(),
        ]);

        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $user = Auth::user();
        $notification = Notification::findOrFail($request->notification_id);

        \Log::info('Verificando permissão', [
            'user_id' => $user->id,
            'notification_id' => $notification->id,
            'notification_target_type' => $notification->target_type,
            'user_perfis' => $user->perfis()->pluck('descricao')->toArray(),
        ]);

        // Verificar se o usuário tem permissão para ver esta notificação
        if (!$notification->isForUser($user)) {
            \Log::warning('Usuário não autorizado para notificação', [
                'user_id' => $user->id,
                'notification_id' => $notification->id,
            ]);
            return response()->json(['error' => 'Não autorizado'], 403);
        }

        $read = $notification->markAsReadByUser($user);

        \Log::info('Notificação marcada como lida', [
            'read_id' => $read->id,
            'is_read' => $read->is_read,
        ]);

        return response()->json([
            'success' => true,
            'read_at' => $read->read_at,
        ]);
    }

    /**
     * API: Marcar todas as notificações como lidas
     */
    public function markAllAsRead()
    {
        $user = Auth::user();

        $notifications = Notification::active()
            ->notExpired()
            ->where(function ($query) use ($user) {
                $query->where('target_type', 'all')
                    ->orWhere('target_type', 'specific')
                    ->whereJsonContains('target_users', $user->id)
                    ->orWhere('target_type', 'role')
                    ->whereJsonContains('target_roles', $user->perfis()->pluck('descricao'));
            })
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsReadByUser($user);
        }

        return response()->json([
            'success' => true,
            'marked_count' => $notifications->count(),
        ]);
    }

    /**
     * Criar registros de leitura para a notificação
     */
    private function createNotificationReads(Notification $notification)
    {
        // Remover registros existentes
        $notification->reads()->delete();

        $targetUsers = [];

        switch ($notification->target_type) {
            case 'all':
                $targetUsers = User::pluck('id')->toArray();
                break;
            
            case 'specific':
                $targetUsers = $notification->target_users ?? [];
                break;
            
            case 'role':
                $targetUsers = User::whereHas('perfis', function ($query) use ($notification) {
                    $query->whereIn('descricao', $notification->target_roles ?? []);
                })->pluck('id')->toArray();
                break;
        }

        // Criar registros de leitura
        foreach ($targetUsers as $userId) {
            NotificationRead::create([
                'user_id' => $userId,
                'notification_id' => $notification->id,
                'is_read' => false,
            ]);
        }
    }
}
