<?php

use App\Models\Sistema\Menu;
use App\Models\Sistema\PadraoTipo;
use App\Models\Sistema\Permissao;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissoes = [
            'cadastro.cliente.index',
            'cadastro.cliente.create',
            'cadastro.cliente.edit',
            'cadastro.cliente.destroy',
            'cadastro.cliente.show',
            'cadastro.cliente.history',
        ];

        foreach ($permissoes as $permissao) {
            DB::table('permissao')->insert(
                [
                    'descricao' => $permissao,
                    'created_at' => now()
                ]
            );
        }

        $menuCadastro = Menu::where('descricao', '=', 'Cadastros')->first();
        $permissaoPadrao = Permissao::where('descricao', '=', 'cadastro.cliente.index')->first();
        $situacaoHabilitado = PadraoTipo::where('descricao', '=', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Cliente',
                'icone' => 'fas fa-user',
                'rota' => 'cadastro.cliente.index',
                'menuPai_id' => $menuCadastro->id,
                'permissao_id' => $permissaoPadrao->id,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ]
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipos de Cliente',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Pessoa Física',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Pessoa Jurídica',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Opção Sim / Não',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Sim',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Não',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Origem do Cliente',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Facebook',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Instagram',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Sites de Busca',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Indicação',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Sem Indicação',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Outros',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);



        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipo de Contato do Cliente',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Comercial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Financeiro',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipo de Endereço do Cliente',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Comercial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Entrega',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Residencial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
