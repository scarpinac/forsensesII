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
            'cadastro.familia.index',
            'cadastro.familia.create',
            'cadastro.familia.edit',
            'cadastro.familia.destroy',
            'cadastro.familia.show',
            'cadastro.familia.history',
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
                'descricao' => 'Família',
                'icone' => 'fas fa-record-vinyl',
                'rota' => 'cadastro.familia.index',
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
