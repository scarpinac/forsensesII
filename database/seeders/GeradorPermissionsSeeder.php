<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GeradorPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'sistema.gerador.index',
            'sistema.gerador.generate',
        ];

        foreach ($permissions as $permission) {
            DB::table('permissao')->updateOrInsert(
                ['descricao' => $permission],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Permissões do gerador criadas com sucesso!');
    }
}
