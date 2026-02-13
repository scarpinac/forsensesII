<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GeradorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            GeradorPermissionsSeeder::class,
            GeradorMenuSeeder::class,
        ]);

        $this->command->info('Gerador de Cadastros configurado com sucesso!');
    }
}
