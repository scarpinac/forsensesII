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

        PadraoTipo::create([
            'descricao' => 'Inclusão/Remoção de Permissões',
            'padrao_id' => $tiposAlteracao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Inclusão/Remoção de Usuários',
            'padrao_id' => $tiposAlteracao->id,
        ]);

        $decisaoSimNao = Padrao::create(['descricao' => 'Decisão - Sim e Não']);

        PadraoTipo::create([
            'descricao' => 'Sim',
            'padrao_id' => $decisaoSimNao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Não',
            'padrao_id' => $decisaoSimNao->id,
        ]);


        $tipoNotificacao = Padrao::create(['descricao' => 'Tipo de Notificação']);

        PadraoTipo::create([
            'descricao' => 'Informação',
            'padrao_id' => $tipoNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Aviso',
            'padrao_id' => $tipoNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Perigo',
            'padrao_id' => $tipoNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Sucesso',
            'padrao_id' => $tipoNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Erro',
            'padrao_id' => $tipoNotificacao->id,
        ]);


        $enviarNotificacao = Padrao::create(['descricao' => 'Enviar Notificação Para']);

        PadraoTipo::create([
            'descricao' => 'Todos os Usuários',
            'padrao_id' => $enviarNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Usuários Específicos',
            'padrao_id' => $enviarNotificacao->id,
        ]);

        PadraoTipo::create([
            'descricao' => 'Perfis Específicos',
            'padrao_id' => $enviarNotificacao->id,
        ]);
    }
}
