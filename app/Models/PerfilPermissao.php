<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PerfilPermissao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perfil_permissao';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'perfil_id',
        'permissao_id',
    ];

    /**
     * Get the perfil's historicos.
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(PerfilPermissaoHistorico::class, 'perfil_permissao_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the perfil that owns the perfil permissao.
     */
    public function perfil(): BelongsTo
    {
        return $this->belongsTo(Perfil::class, 'perfil_id');
    }

    /**
     * Get the permissao that owns the perfil permissao.
     */
    public function permissao(): BelongsTo
    {
        return $this->belongsTo(Permissao::class, 'permissao_id');
    }
}
