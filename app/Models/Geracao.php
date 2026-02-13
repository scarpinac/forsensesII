<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Geracao extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'classe',
        'modulo_pai',
        'soft_delete',
        'timestamps',
        'criar_permissoes',
        'criar_menu',
        'observacoes',
        'situacao_id'
    ];

    protected $casts = [
        'soft_delete' => 'boolean',
        'timestamps' => 'boolean',
        'criar_permissoes' => 'boolean',
        'criar_menu' => 'boolean',
    ];

    /**
     * Get the campos for the geracao.
     */
    public function campos(): HasMany
    {
        return $this->hasMany(GeracaoCampo::class)->orderBy('ordem');
    }

    /**
     * Get the situacao that owns the geracao.
     */
    public function situacao(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    /**
     * Get the table name for the generated class.
     */
    public function getTableName(): string
    {
        return strtolower($this->classe);
    }

    /**
     * Get the namespace for the generated class.
     */
    public function getNamespace(): string
    {
        return 'App\\Models';
    }

    /**
     * Get the controller namespace for the generated class.
     */
    public function getControllerNamespace(): string
    {
        return 'App\\Http\\Controllers\\' . $this->modulo_pai;
    }
}
