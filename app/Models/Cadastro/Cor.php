<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cor';

    protected $fillable = [
        'descricao',
        'corHexadecimal',
    ];

    public function historicos(): HasMany
    {
        return $this->hasMany(CorHistorico::class)->orderBy('created_at', 'desc');
    }
}
