<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padrao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padrao';

    protected $fillable = [
        'descricao',
    ];

    const Situacao = 1;
    const TiposAlteracao = 2;
    const DecisaoSimNao = 3;
    const TipoNotificacao = 4;
    const EnviarNotificacaoPara = 5;

    /**
     * Get the tipos for the padrao.
     */
    public function tipos(): HasMany
    {
        return $this->hasMany(PadraoTipo::class);
    }

    /**
     * Get the historicos for the padrao.
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(PadraoHistorico::class, 'padrao_id')->orderBy('created_at', 'desc');
    }
}
