<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Padrao extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padrao';

    protected $fillable = [
        'descricao',
    ];

    public function tipos()
    {
        return $this->hasMany(PadraoTipo::class);
    }
}
