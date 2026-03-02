<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comissao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'comissao';

    protected $fillable = [
        'valor',
        'tipoComissao_id',
    ];

    protected $casts = [
        'valor' => 'decimal:2',
    ];

    public function tipoComissao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoComissao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ComissaoHistorico::class)->orderBy('created_at', 'desc');
    }
}
