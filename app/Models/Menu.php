<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'menu';

    protected $fillable = [
        'descricao',
        'icone',
        'rota',
        'menuPai_id',
        'permissao_id',
        'situacao_id',
    ];

    public function menuPai(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menuPai_id');
    }

    public function submenus(): HasMany
    {
        return $this->hasMany(Menu::class, 'menuPai_id');
    }

    public function permissao(): BelongsTo
    {
        return $this->belongsTo(Permissao::class);
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(PadraoTipo::class, 'situacao_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(MenuHistorico::class)->orderBy('created_at', 'desc');
    }
}
