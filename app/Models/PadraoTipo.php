<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadraoTipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padrao_tipo';

    protected $fillable = [
        'descricao',
        'padrao_id',
    ];

    public function padrao(): BelongsTo
    {
        return $this->belongsTo(Padrao::class);
    }
}
