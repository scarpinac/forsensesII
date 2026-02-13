<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeradorMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buscar ou criar o menu pai "Sistema"
        $menuSistema = DB::table('menu')->where('descricao', 'Sistema')->first();
        
        if (!$menuSistema) {
            $sistemaPermissao = DB::table('permissao')->where('descricao', 'sistema.index')->first();
            $situacaoAtiva = DB::table('padrao_tipo')
                ->join('padrao', 'padrao_tipo.padrao_id', '=', 'padrao.id')
                ->where('padrao.descricao', 'Situação')
                ->where('padrao_tipo.descricao', 'Ativo')
                ->first();

            $menuSistemaId = DB::table('menu')->insertGetId([
                'descricao' => 'Sistema',
                'icone' => 'fas fa-cogs',
                'rota' => 'sistema.index',
                'menuPai_id' => null,
                'permissao_id' => $sistemaPermissao->id ?? null,
                'situacao_id' => $situacaoAtiva->id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $menuSistemaId = $menuSistema->id;
        }

        // Criar menu do gerador
        $geradorPermissao = DB::table('permissao')->where('descricao', 'sistema.gerador.index')->first();
        $situacaoAtiva = DB::table('padrao_tipo')
            ->join('padrao', 'padrao_tipo.padrao_id', '=', 'padrao.id')
            ->where('padrao.descricao', 'Situação')
            ->where('padrao_tipo.descricao', 'Ativo')
            ->first();

        DB::table('menu')->updateOrInsert(
            ['descricao' => 'Gerador de Cadastros'],
            [
                'icone' => 'fas fa-magic',
                'rota' => 'sistema.gerador.index',
                'menuPai_id' => $menuSistemaId,
                'permissao_id' => $geradorPermissao->id ?? null,
                'situacao_id' => $situacaoAtiva->id ?? 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $this->command->info('Menu do gerador criado com sucesso!');
    }
}
