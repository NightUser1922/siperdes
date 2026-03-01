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
                'username' => 'admin',
                'password' => Hash::make('admin123'),
                'level' => 'Admin',
                'nama_lengkap' => 'Administrator SIPERDES',
            ],
            [
                'username' => 'kades',
                'password' => Hash::make('kades123'),
                'level' => 'Kades',
                'nama_lengkap' => 'Kepala Desa',
            ],
        ]);
    }
}