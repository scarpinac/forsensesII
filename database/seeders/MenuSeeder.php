<?php

namespace Database\Seeders;

use App\Models\Sistema\Menu;
use App\Models\Sistema\PadraoTipo;
use App\Models\Sistema\Permissao;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $situacaoHabilitado = PadraoTipo::where('descricao', 'Habilitado')->first();

        // Menu Pai: Sistema
        $permissaoSistema = Permissao::where('descricao', 'sistema.index')->first();
        $menuSistema = Menu::create([
            'descricao' => 'Sistema',
            'icone' => 'fas fa-microchip',
            'rota' => '#',
            'menuPai_id' => null,
            'permissao_id' => null,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Padrão
        $permissaoPadrao = Permissao::where('descricao', 'sistema.padrao.index')->first();
        Menu::create([
            'descricao' => 'Padrão',
            'icone' => 'fas fa-cogs',
            'rota' => 'sistema.padrao.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoPadrao->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Menu
        $permissaoMenu = Permissao::where('descricao', 'sistema.menu.index')->first();
        Menu::create([
            'descricao' => 'Menu',
            'icone' => 'fas fa-bars',
            'rota' => 'sistema.menu.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoMenu->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Perfil
        $permissaoPerfil = Permissao::where('descricao', 'sistema.perfil.index')->first();
        Menu::create([
            'descricao' => 'Perfil',
            'icone' => 'fas fa-users-slash',
            'rota' => 'sistema.perfil.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoPerfil->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Permissão
        $permissaoPermissao = Permissao::where('descricao', 'sistema.permissao.index')->first();
        Menu::create([
            'descricao' => 'Permissão',
            'icone' => 'fas fa-user-shield',
            'rota' => 'sistema.permissao.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoPermissao->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Usuário
        $permissaoUsuario = Permissao::where('descricao', 'sistema.usuario.index')->first();
        Menu::create([
            'descricao' => 'Usuário',
            'icone' => 'fas fa-user',
            'rota' => 'sistema.usuario.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoUsuario->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Usuário
        $permissaoNotificacao = Permissao::where('descricao', 'sistema.notificacao.index')->first();
        Menu::create([
            'descricao' => 'Notificação',
            'icone' => 'fas fa-bell',
            'rota' => 'sistema.notificacao.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoNotificacao->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Configuração de Api
        $permissaoApi = Permissao::where('descricao', 'sistema.api.index')->first();
        Menu::create([
            'descricao' => 'Configuração de API',
            'icone' => 'fas fa-cogs',
            'rota' => 'sistema.api.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoApi->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Parâmetros
        $permissaoParametro = Permissao::where('descricao', 'sistema.parametro.index')->first();
        Menu::create([
            'descricao' => 'Parâmetros',
            'icone' => 'fas fa-cogs',
            'rota' => 'sistema.parametro.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoParametro->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Submenu: Gerador de Cadastros
        $permissaoGerador = Permissao::where('descricao', 'sistema.gerador.index')->first();
        Menu::create([
            'descricao' => 'Gerador de Cadastros',
            'icone' => 'fas fa-check-double',
            'rota' => 'sistema.gerador.index',
            'menuPai_id' => $menuSistema->id,
            'permissao_id' => $permissaoGerador->id,
            'situacao_id' => $situacaoHabilitado->id,
        ]);

        // Menu Pai: Sistema
        $permissaoCadastro = Permissao::where('descricao', 'cadastros.index')->first();
        $menuCadastros = Menu::create([
            'descricao' => 'Cadastros',
            'icone' => 'fas fa-database',
            'rota' => '#',
            'menuPai_id' => null,
            'permissao_id' => null,
            'situacao_id' => $situacaoHabilitado->id,
        ]);
    }
}
