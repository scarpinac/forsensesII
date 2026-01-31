<?php

namespace Database\Seeders;

use App\Models\Padrao;
use App\Models\PadraoTipo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PadroesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $situacao = Padrao::create(['descricao' => 'Situação']);

        PadraoTipo::create([
            'descricao' => 'Habilitado',
            'padrao_id' => $situacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Desabilitado',
            'padrao_id' => $situacao->id,
        ]);


        $tiposAlteracao = Padrao::create(['descricao' => 'Tipos de Alteração']);

        PadraoTipo::create([
            'descricao' => 'Inclusão de Registro',
            'padrao_id' => $tiposAlteracao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Alteração de Registro',
            'padrao_id' => $tiposAlteracao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Deleção de Registro',
            'padrao_id' => $tiposAlteracao->id,
        ]);

    }
}
