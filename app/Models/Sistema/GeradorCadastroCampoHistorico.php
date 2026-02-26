<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeradorCadastroCampoHistorico extends Model
{
    use HasFactory;

    protected $table = 'gerador_cadastros_campos_historico';

    protected $fillable = [
        'user_id',
        'campo_id',
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
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the campo that owns the historico.
     */
    public function campo()
    {
        return $this->belongsTo(GeradorCadastroCampo::class);
    }

    /**
     * Get the tipoAlteracao that owns the historico.
     */
    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class);
    }
}
