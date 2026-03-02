<?php

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
            'cadastro.produto.index',
            'cadastro.produto.create',
            'cadastro.produto.edit',
            'cadastro.produto.destroy',
            'cadastro.produto.show',
            'cadastro.produto.history',
        ];

        foreach ($permissoes as $permissao) {
            DB::table('permissao')->insert(
                [
                    'descricao' => $permissao,
                    'created_at' => now()
                ]
            );
        }

        $menuCadastro = \App\Models\Sistema\Menu::where('descricao', '=', 'Cadastros')->first();
        $permissaoPadrao = \App\Models\Sistema\Permissao::where('descricao', '=', 'cadastro.cliente.index')->first();
        $situacaoHabilitado = \App\Models\Sistema\PadraoTipo::where('descricao', '=', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Produto',
                'icone' => 'fas fa-music',
                'rota' => 'cadastro.produto.index',
                'menuPai_id' => $menuCadastro->id,
                'permissao_id' => $permissaoPadrao->id,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ]
        ]);

        $padrao = DB::table('padrao')->insertGetId([
            'descricao' => 'Tipos de Acabamento',
            'created_at' => now()
        ]);

        DB::table('padrao_tipo')->insert([
            'descricao' => 'Alumínio',
            'padrao_id' => $padrao,
            'created_at' => now()
        ]);
        DB::table('padrao_tipo')->insert([
            'descricao' => 'Dekton',
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
