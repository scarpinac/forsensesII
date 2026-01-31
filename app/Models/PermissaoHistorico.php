<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PermissaoHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissao_historico';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'permissao_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the permission that was changed.
     */
    public function permissao()
    {
        return $this->belongsTo(Permissao::class);
    }

    /**
     * Get the type of change.
     */
    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
