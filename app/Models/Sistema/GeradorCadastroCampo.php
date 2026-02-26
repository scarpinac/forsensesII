<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeradorCadastroCampo extends Model
{
    use HasFactory;

    protected $table = 'gerador_cadastros_campos';

    protected $fillable = [
        'gerador_id',
        'nome',
        'tipo_id',
        'tamanho_maximo',
        'relacionamento',
        'obrigatorio',
        'unico',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'unico' => 'boolean',
        'tamanho_maximo' => 'integer',
    ];

    /**
     * Get the gerador that owns the campo.
     */
    public function gerador(): BelongsTo
    {
        return $this->belongsTo(GeradorCadastros::class, 'gerador_id', 'id');
    }

    /**
     * Get the tipo that owns the campo.
     */
    public function tipo(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'tipo_id', 'id');
    }
}
