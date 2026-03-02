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
            'cadastro.comissao.index',
            'cadastro.comissao.create',
            'cadastro.comissao.edit',
            'cadastro.comissao.destroy',
            'cadastro.comissao.show',
            'cadastro.comissao.history',
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
        $permissaoPadrao = Permissao::where('descricao', '=', 'cadastro.comissao.index')->first();
        $situacaoHabilitado = PadraoTipo::where('descricao', '=', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Comissão',
                'icone' => 'fas fa-money-bill-wave',
                'rota' => 'cadastro.comissao.index',
                'menuPai_id' => $menuCadastro->id,
                'permissao_id' => $permissaoPadrao->id,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ]
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipos de Comissão',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Em porcentagem',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Em valor monetário',
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
