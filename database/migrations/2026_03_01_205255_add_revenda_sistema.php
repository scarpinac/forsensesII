<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Sistema\Menu;
use App\Models\Sistema\PadraoTipo;
use App\Models\Sistema\Permissao;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissoes = [
            'cadastro.revenda.index',
            'cadastro.revenda.create',
            'cadastro.revenda.edit',
            'cadastro.revenda.destroy',
            'cadastro.revenda.show',
            'cadastro.revenda.history',
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
        $permissaoPadrao = Permissao::where('descricao', '=', 'cadastro.revenda.index')->first();
        $situacaoHabilitado = PadraoTipo::where('descricao', '=', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Revenda',
                'icone' => 'fas fa-warehouse',
                'rota' => 'cadastro.revenda.index',
                'menuPai_id' => $menuCadastro->id,
                'permissao_id' => $permissaoPadrao->id,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ]
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipos de Revenda',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Filial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Parceiro Comercial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipo de Regime',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Grande Empresa',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'ME',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'MEI',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Médio Porte',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'PP',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipo de Showroom',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Casa',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Loja de Rua',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Sala Comercial',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Origem Cadastro',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Site',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Sistema',
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
