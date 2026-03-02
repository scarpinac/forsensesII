<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransportadoraHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transportadora_historico';

    protected $fillable = [
        'user_id',
        'transportadora_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\User::class);
    }

    public function transportadora(): BelongsTo
    {
        return $this->belongsTo(Transportadora::class);
    }

    public function tipoAlteracao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoAlteracao_id');
    }
}
