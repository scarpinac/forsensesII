<?php

namespace App\Models\Cadastro;

use App\Models\Cadastro\Revenda;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CondicaoPagamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'condicao_pagamento';

    protected $fillable = [
        'descricao',
        'revenda_id',
        'diasEntreParcelas',
        'quantidadeParcelas',
        'situacao_id',
    ];

    protected $casts = [
        'diasEntreParcelas' => 'integer',
        'quantidadeParcelas' => 'integer',
    ];

    public function revenda()
    {
        return $this->belongsTo(Revenda::class);
    }

    public function situacao()
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(CondicaoPagamentoHistorico::class)->orderBy('created_at', 'desc');
    }
}
