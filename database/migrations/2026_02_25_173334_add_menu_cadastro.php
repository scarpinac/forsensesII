<?php

use App\Models\Sistema\PadraoTipo;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $situacaoHabilitado = PadraoTipo::where('descricao', 'Habilitado')->first();

        DB::table('menu')->insert([
            [
                'descricao' => 'Cadastros',
                'icone' => 'fas fa-database',
                'rota' => '#',
                'menuPai_id' => null,
                'permissao_id' => null,
                'situacao_id' => $situacaoHabilitado->id,
                'created_at' => now()
            ],
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
