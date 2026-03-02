<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Revenda extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'revenda';

    protected $fillable = [
        'tipoRevenda_id',
        'nomeFantasia',
        'razaoSocial',
        'matrizRevenda_id',
        'dataCriacao',
        'cnpj',
        'inscricaoEstadual',
        'inscricaoMunicipal',
        'optanteSimples_id',
        'responsavelNome',
        'responsavelRg',
        'responsavelRgOrgaoEmissor',
        'responsavelCpf',
        'observacao',
        'situacao_id',
        'nivelDesconto',
        'dataUltimaAlteracaoDesconto',
        'descontoInicial',
        'descontoMaximo',
        'descontoAtual',
        'token',
        'dataAberturaRevenda',
        'ramoAtividade',
        'tipoRegime_id',
        'fornecedoresAudio',
        'fornecedoresVideo',
        'fornecedoresAutomacao',
        'tipoShowroom_id',
        'areaExposicao',
        'temJardim',
        'descontoVitalicio',
        'dataAprovacaoCadastro',
        'origemCadastro_id',
    ];

    protected $casts = [
        'dataCriacao' => 'datetime',
        'dataUltimaAlteracaoDesconto' => 'datetime',
        'dataAberturaRevenda' => 'date',
        'dataAprovacaoCadastro' => 'datetime',
        'descontoInicial' => 'decimal:2',
        'descontoMaximo' => 'decimal:2',
        'descontoAtual' => 'decimal:2',
        'areaExposicao' => 'decimal:2',
        'temJardim' => 'boolean',
        'descontoVitalicio' => 'boolean',
    ];

    // Relacionamentos (baseado no exemplo de Menu, com BelongsTo para padrao_tipo e self-reference)
    public function tipoRevenda(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoRevenda_id');
    }

    public function matrizRevenda(): BelongsTo
    {
        return $this->belongsTo(Revenda::class, 'matrizRevenda_id');
    }

    public function optanteSimples(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'optanteSimples_id');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'situacao_id');
    }

    public function tipoRegime(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoRegime_id');
    }

    public function tipoShowroom(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoShowroom_id');
    }

    public function origemCadastro(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'origemCadastro_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(RevendaHistorico::class, 'revenda_id');
    }
}
