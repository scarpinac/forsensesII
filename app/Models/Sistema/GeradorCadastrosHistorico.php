<?php

namespace App\Models\Sistema;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeradorCadastrosHistorico extends Model
{
    use HasFactory;

    protected $table = 'gerador_cadastros_historico';

    protected $fillable = [
        'user_id',
        'gerador_id',
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
     * Get the gerador that owns the historico.
     */
    public function gerador()
    {
        return $this->belongsTo(GeradorCadastros::class);
    }

    /**
     * Get the tipoAlteracao that owns the historico.
     */
    public function tipoAlteracao()
    {
        return $this->belongsTo(PadraoTipo::class);
    }
}
