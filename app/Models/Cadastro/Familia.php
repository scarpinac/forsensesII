<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Familia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'familia';

    protected $fillable = [
        'descricao',
    ];

    public function historicos(): HasMany
    {
        return $this->hasMany(FamiliaHistorico::class)->orderBy('created_at', 'desc');
    }
}
