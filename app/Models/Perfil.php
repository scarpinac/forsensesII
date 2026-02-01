<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Perfil extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'perfil';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'descricao',
    ];

    /**
     * Get the perfil's historicos.
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(PerfilHistorico::class, 'perfil_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get the perfil permissoes.
     */
    public function perfilPermissoes(): HasMany
    {
        return $this->hasMany(PerfilPermissao::class, 'perfil_id');
    }

    /**
     * Get the permissoes for the perfil.
     */
    public function permissoes()
    {
        return $this->belongsToMany(Permissao::class, 'perfil_permissao', 'perfil_id', 'permissao_id');
    }
}
