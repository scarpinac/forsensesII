<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClienteEndereco extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cliente_endereco';

    protected $fillable = [
        'cliente_id',
        'tipoEndereco_id',
        'cep',
        'logradouro',
        'numero',
        'complemento',
        'bairro',
        'cidade',
        'estado',
        'pais',
        'situacao_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relacionamentos
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function tipoEndereco(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoEndereco_id');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'situacao_id');
    }

    // Scopes
    public function scopePrincipais($query)
    {
        return $query->whereHas('tipoEndereco', function ($q) {
            $q->where('descricao', 'Principal');
        });
    }

    public function scopeEntrega($query)
    {
        return $query->whereHas('tipoEndereco', function ($q) {
            $q->where('descricao', 'Entrega');
        });
    }

    public function scopeCobranca($query)
    {
        return $query->whereHas('tipoEndereco', function ($q) {
            $q->where('descricao', 'Cobrança');
        });
    }

    public function scopeAtivos($query)
    {
        return $query->whereHas('situacao', function ($q) {
            $q->where('descricao', 'Ativo');
        });
    }

    // Accessors
    public function getEnderecoCompletoAttribute(): string
    {
        $endereco = "{$this->logradouro}, {$this->numero}";
        
        if ($this->complemento) {
            $endereco .= " - {$this->complemento}";
        }
        
        $endereco .= " - {$this->bairro}";
        $endereco .= ", {$this->cidade} - {$this->estado}";
        $endereco .= " | CEP: {$this->cep}";
        
        return $endereco;
    }

    public function getEnderecoFormatadoAttribute(): string
    {
        return "{$this->logradouro}, {$this->numero}" . 
               ($this->complemento ? " - {$this->complemento}" : "") . 
               ", {$this->bairro}, {$this->cidade}/{$this->estado}";
    }

    // Mutators
    public function setCepAttribute($value)
    {
        $this->attributes['cep'] = preg_replace('/[^0-9]/', '', $value);
    }

    public function setEstadoAttribute($value)
    {
        $this->attributes['estado'] = strtoupper($value);
    }
}
