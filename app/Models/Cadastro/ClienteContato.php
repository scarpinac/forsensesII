<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteContato extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cliente_contatos';

    protected $fillable = [
        'cliente_id',
        'tipoContato_id',
        'telefone',
        'contato',
        'email',
        'observacoes',
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

    public function tipoContato(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoContato_id');
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'situacao_id');
    }

    // Scopes
    public function scopeTelefones($query)
    {
        return $query->whereHas('tipoContato', function ($q) {
            $q->whereIn('descricao', ['Telefone', 'Telefone Comercial', 'Telefone Residencial']);
        });
    }

    public function scopeCelulares($query)
    {
        return $query->whereHas('tipoContato', function ($q) {
            $q->whereIn('descricao', ['Celular', 'WhatsApp']);
        });
    }

    public function scopeEmails($query)
    {
        return $query->whereHas('tipoContato', function ($q) {
            $q->where('descricao', 'Email');
        });
    }

    public function scopeAtivos($query)
    {
        return $query->whereHas('situacao', function ($q) {
            $q->where('descricao', 'Ativo');
        });
    }

    public function scopePrincipais($query)
    {
        return $query->where('principal', true);
    }

    // Accessors
    public function getContatoFormatadoAttribute(): string
    {
        $tipo = strtolower($this->tipoContato->descricao ?? '');

        if (in_array($tipo, ['telefone', 'celular', 'whatsapp'])) {
            return $this->formatarTelefone($this->contato);
        }

        return $this->contato;
    }

    public function getTipoFormatadoAttribute(): string
    {
        return $this->tipoContato->descricao ?? '';
    }

    public function getIconeAttribute(): string
    {
        $tipo = strtolower($this->tipoContato->descricao ?? '');

        return match($tipo) {
            'telefone' => 'fas fa-phone',
            'celular' => 'fas fa-mobile-alt',
            'whatsapp' => 'fab fa-whatsapp',
            'email' => 'fas fa-envelope',
            default => 'fas fa-address-book',
        };
    }

    // Métodos auxiliares
    private function formatarTelefone($telefone): string
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);

        if (strlen($telefone) === 11) {
            return '(' . substr($telefone, 0, 2) . ') ' .
                   substr($telefone, 2, 5) . '-' .
                   substr($telefone, 7, 4);
        }

        if (strlen($telefone) === 10) {
            return '(' . substr($telefone, 0, 2) . ') ' .
                   substr($telefone, 2, 4) . '-' .
                   substr($telefone, 6, 4);
        }

        return $telefone;
    }

    // Mutators
    public function setContatoAttribute($value)
    {
        $tipo = strtolower($this->tipoContato->descricao ?? '');

        if (in_array($tipo, ['telefone', 'celular', 'whatsapp'])) {
            $this->attributes['contato'] = preg_replace('/[^0-9]/', '', $value);
        } else {
            $this->attributes['contato'] = strtolower(trim($value));
        }
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = strtolower(trim($value));
    }
}
