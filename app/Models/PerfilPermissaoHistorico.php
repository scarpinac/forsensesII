<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerfilPermissaoHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perfil_permissao_historico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'perfil_permissao_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
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
     * Get the user that performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the perfil permissao that was changed.
     */
    public function perfilPermissao(): BelongsTo
    {
        return $this->belongsTo(PerfilPermissao::class, 'perfil_permissao_id');
    }

    /**
     * Get the type of change.
     */
    public function tipoAlteracao(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
