<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cliente extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cliente';

    protected $fillable = [
        'revenda_id',
        'tipoCliente_id',
        'nome',
        'nomeFantasia',
        'nomePreferencia',
        'dataNascimento',
        'rg',
        'rgOrgaoEmissor',
        'cpf',
        'cnpj',
        'inscricaoEstadual',
        'inscricaoMunicipal',
        'optanteSimples_id',
        'responsavelNome',
        'responsavelRg',
        'responsavelRgOrgaoEmissor',
        'responsavelCpf',
        'observacoes',
        'data_cadastro',
        'limite_credito',
        'origem_id',
        'outra_origem',
        'situacao_id',
    ];

    protected $casts = [
        'dataNascimento' => 'date',
        'data_cadastro' => 'date',
        'limite_credito' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relacionamentos
    public function revenda(): BelongsTo
    {
        return $this->belongsTo(Revenda::class, 'revenda_id');
    }

    public function tipoCliente(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoCliente_id');
    }

    public function optanteSimples(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'optanteSimples_id');
    }

    public function origem(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'origem_id');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'situacao_id');
    }

    public function enderecos(): HasMany
    {
        return $this->hasMany(ClienteEndereco::class, 'cliente_id');
    }

    public function contatos(): HasMany
    {
        return $this->hasMany(ClienteContato::class, 'cliente_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(ClienteHistorico::class, 'cliente_id');
    }

    // Scopes
    public function scopePessoaFisica($query)
    {
        return $query->whereHas('tipoCliente', function ($q) {
            $q->where('descricao', 'Pessoa Física');
        });
    }

    public function scopePessoaJuridica($query)
    {
        return $query->whereHas('tipoCliente', function ($q) {
            $q->where('descricao', 'Pessoa Jurídica');
        });
    }

    public function scopeAtivos($query)
    {
        return $query->whereHas('situacao', function ($q) {
            $q->where('descricao', 'Ativo');
        });
    }

    // Accessors
    public function getTipoPessoaAttribute(): string
    {
        return $this->tipoCliente->descricao ?? '';
    }

    public function getNomeCompletoAttribute(): string
    {
        return $this->nomePreferencia ?? $this->nome;
    }

    public function getDocumentoPrincipalAttribute(): string
    {
        return $this->cpf ?: $this->cnpj ?: '';
    }

    public function getTipoDocumentoAttribute(): string
    {
        return $this->cpf ? 'CPF' : 'CNPJ';
    }
}
