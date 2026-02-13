<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Api extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'api';

    protected $fillable = [
        'api_id',
        'credencial',
        'situacao_id',
    ];

    public function api()
    {
        return $this->belongsTo(PadraoTipo::class, 'api_id');
    }

    public function situacao()
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ApiHistorico::class)->orderBy('created_at', 'desc');
    }
}
