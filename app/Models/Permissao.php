<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permissao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'permissao';

    protected $fillable = [
        'descricao',
    ];

    public function historicos(): HasMany
    {
        return $this->hasMany(PermissaoHistorico::class)->orderBy('created_at', 'desc');
    }
}
