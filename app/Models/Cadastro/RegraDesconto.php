<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegraDesconto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'regra_desconto';

    protected $fillable = [
        'descricao',
        'periodo',
        'valorBase',
        'descontoAcrescido',
    ];

    protected $casts = [
        'periodo' => 'integer',
        'valorBase' => 'decimal:2',
        'descontoAcrescido' => 'decimal:2',
    ];

    public function historicos(): HasMany
    {
        return $this->hasMany(RegraDescontoHistorico::class)->orderBy('created_at', 'desc');
    }
}
