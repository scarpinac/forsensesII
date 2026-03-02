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
            'cadastro.origem_produto.index',
            'cadastro.origem_produto.create',
            'cadastro.origem_produto.edit',
            'cadastro.origem_produto.destroy',
            'cadastro.origem_produto.show',
            'cadastro.origem_produto.history',
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
                'descricao' => 'Origem do Produto',
                'icone' => 'fas fa-ship',
                'rota' => 'cadastro.origem_produto.index',
                'menuPai_id' => $menuCadastro->id,
                'permissao_id' => $permissaoPadrao->id,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ]
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
