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
            'cadastro.acabamento.index',
            'cadastro.acabamento.create',
            'cadastro.acabamento.edit',
            'cadastro.acabamento.destroy',
            'cadastro.acabamento.show',
            'cadastro.acabamento.history',
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
                'descricao' => 'Acabamento',
                'icone' => 'fas fa-play-circle',
                'rota' => 'cadastro.acabamento.index',
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
