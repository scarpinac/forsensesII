<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificacaoHistorico extends Model
{
    use HasFactory;

    protected $table = 'notificacao_historico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'padrao_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
        'notificacao_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the tipoAlteracao that owns the historico.
     */
    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }

    /**
     * Scope para alterações de um usuário
     */
    public function scopeDoUsuario($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope para alterações de uma notificação
     */
    public function scopeDaNotificacao($query, $notificacaoId)
    {
        return $query->where('notificacao_id', $notificacaoId);
    }

    /**
     * Scope para alterações recentes
     */
    public function scopeRecentes($query, int $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    /**
     * Obter descrição formatada do tipo
     */
    public function getTipoFormatadoAttribute(): string
    {
        $tipos = [
            'create' => 'Criação',
            'update' => 'Atualização',
            'delete' => 'Exclusão',
            'restore' => 'Restauração',
            'force_delete' => 'Exclusão Permanente',
            'read' => 'Leitura',
            'unread' => 'Não Lida',
        ];

        return $tipos[$this->tipo_alteracao] ?? $this->tipo_alteracao;
    }

    /**
     * Obter cor do tipo para exibição
     */
    public function getCorTipoAttribute(): string
    {
        $cores = [
            'create' => 'success',
            'update' => 'info',
            'delete' => 'danger',
            'restore' => 'warning',
            'force_delete' => 'dark',
            'read' => 'primary',
            'unread' => 'secondary',
        ];

        return $cores[$this->tipo_alteracao] ?? 'secondary';
    }

    /**
     * Obter ícone do tipo
     */
    public function getIconeTipoAttribute(): string
    {
        $icones = [
            'create' => 'fas fa-plus',
            'update' => 'fas fa-edit',
            'delete' => 'fas fa-trash',
            'restore' => 'fas fa-undo',
            'force_delete' => 'fas fa-times',
            'read' => 'fas fa-check',
            'unread' => 'fas fa-envelope',
        ];

        return $icones[$this->tipo_alteracao] ?? 'fas fa-info';
    }

    /**
     * Verificar se é alteração de leitura
     */
    public function isAlteracaoLeitura(): bool
    {
        return in_array($this->tipo_alteracao, ['read', 'unread']);
    }

    /**
     * Obter resumo das alterações
     */
    public function getResumoAlteracoesAttribute(): string
    {
        if ($this->isAlteracaoLeitura()) {
            return $this->descricao;
        }

        if ($this->valores_antigos && $this->valores_novos) {
            $campos = array_keys($this->valores_novos);
            return 'Campos alterados: ' . implode(', ', $campos);
        }

        return $this->descricao;
    }
}
