<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacaoUsuario extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notificacao_usuario';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'notificacao_id',
        'lida',
        'lida_em',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'lida' => 'boolean',
        'lida_em' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relacionamento com a notificação
     */
    public function notificacao(): BelongsTo
    {
        return $this->belongsTo(Notificacao::class, 'notificacao_id');
    }

    /**
     * Relacionamento com o usuário
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Marcar como lida
     */
    public function marcarComoLida(): bool
    {
        $this->lida = true;
        $this->lida_em = now();
        $salvou = $this->save();

        if ($salvou) {
            // Registrar no histórico
            NotificacaoObserver::registrarLeitura($this->notificacao, $this->user_id, 'read');
        }

        return $salvou;
    }

    /**
     * Marcar como não lida
     */
    public function marcarComoNaoLida(): bool
    {
        $this->lida = false;
        $this->lida_em = null;
        $salvou = $this->save();

        if ($salvou) {
            // Registrar no histórico
            NotificacaoObserver::registrarLeitura($this->notificacao, $this->user_id, 'unread');
        }

        return $salvou;
    }

    /**
     * Scope para leituras lidas
     */
    public function scopeLidas($query)
    {
        return $query->where('lida', true);
    }

    /**
     * Scope para leituras não lidas
     */
    public function scopeNaoLidas($query)
    {
        return $query->where('lida', false);
    }

    /**
     * Scope para leituras de um usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para leituras de uma notificação
     */
    public function scopeDaNotificacao($query, $notificacaoId)
    {
        return $query->where('notificacao_id', $notificacaoId);
    }

    /**
     * Verificar se está lida
     */
    public function estaLida(): bool
    {
        return $this->lida;
    }

    /**
     * Obter tempo de leitura formatado
     */
    public function getTempoLeituraAttribute(): ?string
    {
        return $this->lida_em ? $this->lida_em->diffForHumans() : null;
    }
}
