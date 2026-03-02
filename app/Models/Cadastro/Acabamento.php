<?php

namespace App\Models\Cadastro;

use App\Models\Cadastro\Cor;
use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Acabamento extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'acabamento';

    protected $fillable = [
        'descricao',
        'tipoAcabamento_id',
        'cor_id',
    ];

    public function tipoAcabamento()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAcabamento_id');
    }

    public function cor()
    {
        return $this->belongsTo(Cor::class);
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(AcabamentoHistorico::class)->orderBy('created_at', 'desc');
    }
}
