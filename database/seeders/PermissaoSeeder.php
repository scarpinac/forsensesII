<?php

namespace Database\Seeders;

use App\Models\Permissao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissoes = [
            'sistema.index',

            'sistema.permissao.index',
            'sistema.permissao.create',
            'sistema.permissao.edit',
            'sistema.permissao.destroy',
            'sistema.permissao.show',
            'sistema.permissao.history',

            'sistema.menu.index',
            'sistema.menu.create',
            'sistema.menu.edit',
            'sistema.menu.destroy',
            'sistema.menu.show',
            'sistema.menu.history',

            'sistema.usuario.index',
            'sistema.usuario.create',
            'sistema.usuario.edit',
            'sistema.usuario.destroy',
            'sistema.usuario.show',
            'sistema.usuario.history',

            'sistema.padrao.index',
            'sistema.padrao.create',
            'sistema.padrao.edit',
            'sistema.padrao.destroy',
            'sistema.padrao.show',
            'sistema.padrao.history',

            'sistema.padrao.padraoTipo.index',
            'sistema.padrao.padraoTipo.create',
            'sistema.padrao.padraoTipo.edit',
            'sistema.padrao.padraoTipo.destroy',
            'sistema.padrao.padraoTipo.show',
            'sistema.padrao.padraoTipo.history',
        ];

        foreach ($permissoes as $permissao) {
            Permissao::create(['descricao' => $permissao]);
        }
    }
}
