<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'role' => 'admin',
            'status' => 'active',
            'password' => 'admin',
            'no_induk' => '00.00.0.0000',
        ]);
        User::create([
            'name' => 'dosen',
            'email' => 'dosen@gmail.com',
            'role' => 'dosen',
            'status' => 'active',
            'password' => 'dosen',
            'no_induk' => '21098712',
        ]);
        User::create([
            'name' => 'mahasiswa',
            'email' => 'mahasiswa@gmail.com',
            'role' => 'mahasiswa',
            'status' => 'active',
            'password' => 'mahasiswa',
            'no_induk' => '23.01.0.0001',
        ]);

    }
}
