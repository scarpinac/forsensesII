<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PadraoTipo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padrao_tipo';

    protected $fillable = [
        'descricao',
        'padrao_id',
    ];

    /**
     * Get the padrao that owns the padraoTipo.
     */
    public function padrao(): BelongsTo
    {
        return $this->belongsTo(Padrao::class);
    }

    /**
     * Get the historicos for the padraoTipo.
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(PadraoTipoHistorico::class, 'padrao_tipo_id')->orderBy('created_at', 'desc');
    }
}
