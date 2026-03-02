<?php

namespace App\Models\Cadastro;

use App\Models\Cadastro\Cor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tela extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tela';

    protected $fillable = [
        'descricao',
        'cor_id',
    ];

    public function cor()
    {
        return $this->belongsTo(Cor::class);
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(TelaHistorico::class)->orderBy('created_at', 'desc');
    }
}
