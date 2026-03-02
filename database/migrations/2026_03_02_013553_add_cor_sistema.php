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
            'cadastro.cor.index',
            'cadastro.cor.create',
            'cadastro.cor.edit',
            'cadastro.cor.destroy',
            'cadastro.cor.show',
            'cadastro.cor.history',
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
        $permissaoPadrao = Permissao::where('descricao', '=', 'cadastro.transportadora.index')->first();
        $situacaoHabilitado = PadraoTipo::where('descricao', '=', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Cor',
                'icone' => 'fas fa-palette',
                'rota' => 'cadastro.cor.index',
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
