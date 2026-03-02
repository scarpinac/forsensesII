<?php

namespace App\Models\Cadastro;

use App\Models\Cadastro\Acabamento;
use App\Models\Cadastro\Familia;
use App\Models\Cadastro\Tela;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Produto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'produto';

    protected $fillable = [
        'descricao',
        'codigo',
        'quantidadeVolumes',
        'peso',
        'altura',
        'largura',
        'comprimento',
        'precoUnitario',
        'descontoProduto',
        'familia_id',
        'acabamento_id',
        'tela_id',
        'produtoBase',
        'produtoBase_id',
        'especificacao',
        'observacao',
        'permitirVenda',
        'permitirTela',
        'codigoBarras',
        'ncm',
        'cst',
        'cest',
        'origem_id',
    ];

    protected $casts = [
        'quantidadeVolumes' => 'integer',
        'peso' => 'double',
        'altura' => 'double',
        'largura' => 'double',
        'comprimento' => 'double',
        'precoUnitario' => 'decimal:2',
        'descontoProduto' => 'decimal:2',
        'produtoBase' => 'boolean',
        'permitirVenda' => 'boolean',
        'permitirTela' => 'boolean',
        'ncm' => 'integer',
        'cst' => 'integer',
        'cest' => 'integer',
    ];

    public function familia()
    {
        return $this->belongsTo(Familia::class);
    }

    public function acabamento()
    {
        return $this->belongsTo(Acabamento::class);
    }

    public function tela()
    {
        return $this->belongsTo(Tela::class);
    }

    public function produtoBase()
    {
        return $this->belongsTo(Produto::class, 'produtoBase_id');
    }

    public function produtosBase()
    {
        return $this->hasMany(Produto::class, 'produtoBase_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ProdutoHistorico::class)->orderBy('created_at', 'desc');
    }
}
