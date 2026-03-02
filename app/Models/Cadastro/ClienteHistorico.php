<?php

namespace App\Models\Cadastro;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClienteHistorico extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cliente_historico';

    protected $fillable = [
        'user_id',
        'cliente_id',
        'dados_anteriores',
        'dados_novos',
        'tipoAlteracao_id',
    ];

    protected $casts = [
        'dados_anteriores' => 'array',
        'dados_novos' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    // Relacionamentos
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function tipoAlteracao(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Sistema\PadraoTipo::class, 'tipoAlteracao_id');
    }

    // Scopes
    public function scopeCadastro($query)
    {
        return $query->whereHas('tipoAlteracao', function ($q) {
            $q->where('descricao', 'Cadastro');
        });
    }

    public function scopeEdicao($query)
    {
        return $query->whereHas('tipoAlteracao', function ($q) {
            $q->where('descricao', 'Edição');
        });
    }

    public function scopeEndereco($query)
    {
        return $query->whereHas('tipoAlteracao', function ($q) {
            $q->where('descricao', 'like', '%Endereço%');
        });
    }

    public function scopeContato($query)
    {
        return $query->whereHas('tipoAlteracao', function ($q) {
            $q->where('descricao', 'like', '%Contato%');
        });
    }

    public function scopePorPeriodo($query, $dataInicio, $dataFim)
    {
        return $query->whereBetween('created_at', [$dataInicio, $dataFim]);
    }

    public function scopeRecentes($query, $dias = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($dias));
    }

    // Accessors
    public function getTipoFormatadoAttribute(): string
    {
        return $this->tipoAlteracao->descricao ?? '';
    }

    public function getDescricaoResumidaAttribute(): string
    {
        $tipo = $this->tipoAlteracao->descricao ?? '';
        $dadosAntigos = $this->dados_anteriores;
        $dadosNovos = $this->dados_novos;

        if ($tipo === 'Cadastro') {
            return 'Cliente cadastrado no sistema';
        }

        if ($tipo === 'Edição' && is_array($dadosAntigos) && is_array($dadosNovos)) {
            $campos = array_keys(array_diff_assoc($dadosNovos, $dadosAntigos));
            return 'Campos alterados: ' . implode(', ', $campos);
        }

        if (str_contains($tipo, 'Endereço')) {
            $endereco = $dadosNovos['logradouro'] ?? '';
            $numero = $dadosNovos['numero'] ?? '';
            return "Endereço: {$endereco}, {$numero}";
        }

        if (str_contains($tipo, 'Contato')) {
            $contato = $dadosNovos['contato'] ?? '';
            $tipoContato = $dadosNovos['tipoContato'] ?? '';
            return "Contato ({$tipoContato}): {$contato}";
        }

        return $tipo;
    }

    public function getAlteracoesAttribute(): array
    {
        $anteriores = $this->dados_anteriores ?? [];
        $novos = $this->dados_novos ?? [];

        $alteracoes = [];

        foreach ($novos as $campo => $valor) {
            if (isset($anteriores[$campo]) && $anteriores[$campo] !== $valor) {
                $alteracoes[$campo] = [
                    'antes' => $anteriores[$campo],
                    'depois' => $valor,
                ];
            }
        }

        return $alteracoes;
    }

    public function getCorTipoAttribute(): string
    {
        $tipo = strtolower($this->tipoAlteracao->descricao ?? '');

        return match($tipo) {
            'cadastro' => 'success',
            'edição' => 'info',
            'endereço adicionado' => 'primary',
            'endereço alterado' => 'warning',
            'endereço excluído' => 'danger',
            'contato adicionado' => 'primary',
            'contato alterado' => 'warning',
            'contato excluído' => 'danger',
            default => 'secondary',
        };
    }

    public function getIconeTipoAttribute(): string
    {
        $tipo = strtolower($this->tipoAlteracao->descricao ?? '');

        return match($tipo) {
            'cadastro' => 'fas fa-user-plus',
            'edição' => 'fas fa-edit',
            'endereço adicionado' => 'fas fa-map-marker-alt',
            'endereço alterado' => 'fas fa-map',
            'endereço excluído' => 'fas fa-trash-alt',
            'contato adicionado' => 'fas fa-phone-alt',
            'contato alterado' => 'fas fa-phone',
            'contato excluído' => 'fas fa-phone-slash',
            default => 'fas fa-history',
        };
    }
}
