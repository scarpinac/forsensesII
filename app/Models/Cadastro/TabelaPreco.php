<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TabelaPreco extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tabela_preco';

    protected $fillable = [
        'descricao',
        'vigenciaAte',
        'situacao_id',
    ];

    protected $casts = [
        'vigenciaAte' => 'datetime',
    ];

    public function situacao()
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(TabelaPrecoHistorico::class)->orderBy('created_at', 'desc');
    }
}
