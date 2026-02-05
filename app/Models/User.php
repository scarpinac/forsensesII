<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class User extends Authenticatable implements HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'admin',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->acceptsMimeTypes(['image/jpeg', 'image/png'])
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('avatar')
                    ->width(33)
                    ->height(33);
            });
    }


    public function getUrlAvatarAttribute() : string
    {
        return ($this->hasMedia('avatar')
        && file_exists($this->getFirstMedia('avatar')->getPath())
            ? ($this->getFirstMedia('avatar')->getUrl())
            : (
            asset('/images/sem_foto.png')
            )
        );
    }

    public function getAvatarAttribute() : string
    {
        return ($this->hasMedia('avatar')
        && file_exists($this->getFirstMedia('avatar')->getPath())
            ? ($this->getFirstMedia('avatar')->getUrl())
            : (
            asset('/images/sem_foto.png')
            )
        );
    }

    /**
     * Get the user's historicos.
     */
    public function historicos(): HasMany
    {
        return $this->hasMany(UserHistorico::class, 'usuario_id')->orderBy('created_at', 'desc');
    }

    public function canAccess($permissao) : bool
    {
        // Se for admin, permite tudo
        if ($this->admin) {
            return true;
        }

        // Obter permissões diretamente do banco em vez da sessão
        $userPermissions = $this->perfis()
            ->with(['perfilPermissoes.permissao'])
            ->get()
            ->flatMap(function ($perfil) {
                return $perfil->perfilPermissoes->pluck('permissao.descricao');
            })
            ->unique()
            ->toArray();

        // Para permissões store/update, verificar se tem create/edit correspondente
        $permissionToCheck = $permissao;
        if (in_array($permissao, ['sistema.usuario.store', 'sistema.perfil.store', 'sistema.permissao.store', 'sistema.menu.store'])) {
            $permissionToCheck = str_replace('.store', '.create', $permissao);
        } elseif (in_array($permissao, ['sistema.usuario.update', 'sistema.perfil.update', 'sistema.permissao.update', 'sistema.menu.update'])) {
            $permissionToCheck = str_replace('.update', '.edit', $permissao);
        }

        return in_array($permissionToCheck, $userPermissions);
    }

    public function adminlte_image()
    {
        if ($this->avatar) {
            $arArquivoFoto = explode('storage', $this->avatar);
            if(isset($arArquivoFoto[1])) {
                return asset('storage' . $arArquivoFoto[1]);
            }
            return asset($this->avatar);
        }

        return asset('images/sem_foto.png');
    }

    /**
     * Get the perfis for the user.
     */
    public function perfis(): BelongsToMany
    {
        return $this->belongsToMany(Perfil::class, 'perfil_usuario', 'user_id', 'perfil_id');
    }


    public function adminlte_profile_url()
    {
        return 'sistema.usuario.edit';
    }
}
