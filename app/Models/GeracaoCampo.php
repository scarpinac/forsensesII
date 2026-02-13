<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeracaoCampo extends Model
{
    use HasFactory;

    protected $table = 'geracao_campos';

    protected $fillable = [
        'geracao_id',
        'nome',
        'tipo',
        'tamanho',
        'decimal_places',
        'obrigatorio',
        'unique',
        'default_value',
        'observacoes',
        'ordem',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'unique' => 'boolean',
        'tamanho' => 'integer',
        'decimal_places' => 'integer',
        'ordem' => 'integer',
    ];

    /**
     * Get the geracao that owns the campo.
     */
    public function geracao(): BelongsTo
    {
        return $this->belongsTo(Geracao::class);
    }

    /**
     * Get the database column type based on the field type.
     */
    public function getDatabaseColumnType(): string
    {
        return match($this->tipo) {
            'string' => "string({$this->tamanho ?? 255})",
            'integer' => 'integer',
            'decimal' => "decimal({$this->tamanho ?? 10}, {$this->decimal_places ?? 2})",
            'date' => 'date',
            'datetime' => 'datetime',
            'boolean' => 'boolean',
            'text' => 'text',
            'longtext' => 'longtext',
            'json' => 'json',
            default => 'string(255)',
        };
    }

    /**
     * Get the Laravel validation rule for this field.
     */
    public function getValidationRule(): array
    {
        $rules = [];

        if ($this->obrigatorio) {
            $rules[] = 'required';
        } else {
            $rules[] = 'nullable';
        }

        if ($this->unique) {
            $rules[] = 'unique:' . $this->geracao->classe . ',' . $this->nome;
        }

        $rules[] = match($this->tipo) {
            'string' => 'string|max:' . ($this->tamanho ?? 255),
            'integer' => 'integer',
            'decimal' => 'numeric',
            'date' => 'date',
            'datetime' => 'date',
            'boolean' => 'boolean',
            'text' => 'string',
            'longtext' => 'string',
            'json' => 'json',
            default => 'string',
        };

        return $rules;
    }
}
