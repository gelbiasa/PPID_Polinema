<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'user_id' => 1,
                'level_id' => 1,
                'username' => 'admin',
                'nama' => 'Gelby Firmansyah',
                'password' => Hash::make('12345'), // class untuk mengenkripsi/hash password
                'no_hp' => '08111',
                'email' => 'gelbifirmansyah12@gmail.com'
            ],
            [
                'user_id' => 2,
                'level_id' => 2,
                'username' => 'agus',
                'nama' => 'Agus Subianto',
                'password' => Hash::make('12345'),
                'no_hp' => '08222',
                'email' => 'agussubianto@gmail.com'
            ],
            [
                'user_id' => 3,
                'level_id' => 3,
                'username' => 'zainal',
                'nama' => 'Zainal Arifin',
                'password' => Hash::make('12345'),
                'no_hp' => '08333',
                'email' => 'zainalarifin@gmail.com'
            ],
            [
                'user_id' => 4,
                'level_id' => 4,
                'username' => 'isroqi',
                'nama' => 'Ahmad Isroqi',
                'password' => Hash::make('12345'),
                'no_hp' => '08444',
                'email' => 'isroqiaja@gmail.com'
            ],
        ];    
        DB::table('m_user')->insert($data);
    }
}
