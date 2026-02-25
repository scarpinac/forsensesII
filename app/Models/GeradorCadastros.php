<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeradorCadastros extends Model
{
    protected $fillable = [
        'classe',
        'menuPai_id',
        'criar_permissoes',
        'criar_menu',
        'soft_delete',
    ];

    protected $casts = [
        'criar_permissoes' => 'boolean',
        'criar_menu' => 'boolean',
        'soft_delete' => 'boolean',
    ];

    /**
     * Get the campos for the gerador.
     */
    public function campos(): HasMany
    {
        return $this->hasMany(GeradorCadastroCampo::class, 'gerador_id', 'id');
    }

    /**
     * Get the menu that owns the gerador.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menuPai_id');
    }
}
