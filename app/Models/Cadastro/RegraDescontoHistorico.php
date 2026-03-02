<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RegraDescontoHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'regra_desconto_historico';

    protected $fillable = [
        'user_id',
        'regra_desconto_id',
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

    public function regraDesconto()
    {
        return $this->belongsTo(RegraDesconto::class);
    }

    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
