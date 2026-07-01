<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tb_user')->insert([
            [
                'nama' => 'Administrator SIPERDES',
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'role' => 'Admin',
            ],
            [
                'nama' => 'Kepala Desa',
                'username' => 'kades',
                'password' => Hash::make('kades123'),
                'role' => 'Kepala Desa',
            ],
        ]);
    }
}
