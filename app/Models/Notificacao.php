<?php

namespace App\Models;

use App\Observers\NotificacaoObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notificacao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificacao';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'titulo',
        'mensagem',
        'tipoNotificacao_id',
        'enviarNotificacaoPara_id',
        'enviado_para',
        'icone',
        'enviar_em',
        'enviado',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'enviar_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com tipo de notificação
     */
    public function tipoNotificacao(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoNotificacao_id');
    }

    /**
     * Relacionamento com tipo de notificação
     */
    public function enviarNotificacaoPara(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'enviarNotificacaoPara_id');
    }

    /**
     * Relacionamento com leituras da notificação
     */
    public function leituras(): HasMany
    {
        return $this->hasMany(NotificacaoUsuario::class, 'notificacao_id');
    }

    /**
     * Relacionamento com histórico
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(NotificacaoHistorico::class, 'notificacao_id')->orderBy('created_at', 'desc');
    }

    /**
     * Scope para notificações não expiradas
     */
    public function scopeNaoExpirado($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expira_em')
              ->orWhere('expira_em', '>', now());
        });
    }

    /**
     * Verifica se a notificação expirou
     */
    public function estaExpirada(): bool
    {
        return $this->expira_em && $this->expira_em->isPast();
    }

    /**
     * Obtém a leitura de um usuário específico
     */
    public function getLeituraParaUsuario($user): ?NotificacaoUsuario
    {
        return $this->leituras()->where('user_id', $user->id)->first();
    }

    /**
     * Marcar notificação como lida por um usuário
     */
    public function marcarComoLidaPorUsuario($user): NotificacaoUsuario
    {
        $leitura = $this->leituras()->updateOrCreate(
            ['user_id' => $user->id],
            ['lida' => true, 'lida_em' => now()]
        );

        // Registrar no histórico
        NotificacaoObserver::registrarLeitura($this, $user->id, 'read');

        return $leitura;
    }

    /**
     * Marcar notificação como não lida por um usuário
     */
    public function marcarComoNaoLidaPorUsuario($user): NotificacaoUsuario
    {
        $leitura = $this->leituras()->updateOrCreate(
            ['user_id' => $user->id],
            ['lida' => false, 'lida_em' => null]
        );

        // Registrar no histórico
        NotificacaoObserver::registrarLeitura($this, $user->id, 'unread');

        return $leitura;
    }

    /**
     * Verificar se foi lida por um usuário
     */
    public function foiLidaPorUsuario($user): bool
    {
        $leitura = $this->getLeituraParaUsuario($user);
        return $leitura ? $leitura->lida : false;
    }

    /**
     * Obter contagem de leituras
     */
    public function getContagemLeiturasAttribute(): array
    {
        $total = $this->leituras()->count();
        $lidas = $this->leituras()->where('lida', true)->count();
        $naoLidas = $total - $lidas;

        return [
            'total' => $total,
            'lidas' => $lidas,
            'nao_lidas' => $naoLidas,
        ];
    }

    /**
     * Obter tipo formatado
     */
    public function getTipoFormatadoAttribute(): string
    {
        return $this->tipoNotificacao ? $this->tipoNotificacao->descricao : 'N/A';
    }

    /**
     * Obter cor do tipo para exibição
     */
    public function getCorTipoAttribute(): string
    {
        if (!$this->tipoNotificacao) {
            return 'secondary';
        }

        // Mapear tipos para cores (baseado no padrão do sistema)
        $cores = [
            'info' => 'info',
            'sucesso' => 'success',
            'aviso' => 'warning',
            'erro' => 'danger',
        ];

        return $cores[strtolower($this->tipoNotificacao->descricao)] ?? 'secondary';
    }
}
