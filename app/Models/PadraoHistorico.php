<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PadraoHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'padrao_historico';

    protected $fillable = [
        'user_id',
        'padrao_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    /**
     * Get the user that owns the historico.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the padrao that owns the historico.
     */
    public function padrao(): BelongsTo
    {
        return $this->belongsTo(Padrao::class);
    }

    /**
     * Get the tipoAlteracao that owns the historico.
     */
    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
