<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

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
    const ValoresParametros = 6;
    const Api = 7;
    const TipoCamposGerador = 8;

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
