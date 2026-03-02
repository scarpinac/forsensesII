<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transportadora extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transportadora';

    protected $fillable = [
        'nomeFantasia',
        'razaoSocial',
        'cnpj',
        'situacao_id',
        'observacao',
        'estadosAtendidos',
    ];

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(TransportadoraHistorico::class)->orderBy('created_at', 'desc');
    }
}
