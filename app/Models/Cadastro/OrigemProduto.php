<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrigemProduto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'origem_produto';

    protected $fillable = [
        'codigo',
        'descricao',
        'situacao_id',
    ];

    public function situacao()
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(OrigemProdutoHistorico::class)->orderBy('created_at', 'desc');
    }
}
