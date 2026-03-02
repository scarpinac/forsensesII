<?php

namespace App\Models\Cadastro;

use App\Models\Sistema\PadraoTipo;
use App\Models\Sistema\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RevendaHistorico extends Model
{
    use HasFactory;

    protected $table = 'revenda_historico';

    protected $fillable = [
        'user_id',
        'revenda_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function revenda(): BelongsTo
    {
        return $this->belongsTo(Revenda::class);
    }

    public function tipoAlteracao(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'tipoAlteracao_id');
    }
}
