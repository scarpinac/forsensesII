<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondicaoPagamentoHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'condicao_pagamento_historico';

    protected $fillable = [
        'user_id',
        'condicao_pagamento_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(\App\Models\Sistema\User::class);
    }

    public function condicaoPagamento()
    {
        return $this->belongsTo(CondicaoPagamento::class);
    }

    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
