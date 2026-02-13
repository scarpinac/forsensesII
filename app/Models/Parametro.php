<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametro extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'parametro';

    protected $fillable = [
        'nome',
        'descricao',
        'tipo_id',
        'valor',
    ];

    public function tipo()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipo_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ParametroHistorico::class)->orderBy('created_at', 'desc');
    }
}
