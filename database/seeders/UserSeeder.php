<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'henriquesgomes@gmail.com'],
            [
                'name' => '(SysAdmin) Henrique',
                'admin' => 1,
                'password' => Hash::make('pwdlve147'),
            ]
        );
    }
}
