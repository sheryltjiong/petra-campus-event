<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14230001',
            'jurusan' => 'Informatika',
            'line_id' => 'super.line',
            'whatsapp' => '6281234567890',
            'role' => 'super_admin',
        ]);

        // Admins
        User::create([
            'name' => 'Admin 1',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14200991',
            'jurusan' => 'Informatika',
            'line_id' => 'admin.line',
            'whatsapp' => '6281234567891',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14200092',
            'jurusan' => 'Sistem Informasi Bisnis',
            'line_id' => 'admin2.line',
            'whatsapp' => '6281234567893',
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin 3',
            'email' => 'admin3@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14200093',
            'jurusan' => 'Sistem Informasi Bisnis',
            'line_id' => 'admin3.line',
            'whatsapp' => '6281234567894',
            'role' => 'admin',
        ]);

        // Users (Mahasiswa)
        User::create([
            'name' => 'Livia Gabrielle',
            'email' => 'user@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14230196',
            'jurusan' => 'Informatika',
            'line_id' => 'livia.line',
            'whatsapp' => '6281234567895',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Fernando Hose',
            'email' => 'fernando@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14220151',
            'jurusan' => 'Informatika',
            'line_id' => 'fernando.line',
            'whatsapp' => '6281234567896',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Jean Sheryl',
            'email' => 'jean@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14230269',
            'jurusan' => 'Data Science & Analytics',
            'line_id' => 'jean.line',
            'whatsapp' => '6281234567897',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Kenenza Davelynne',
            'email' => 'kenza@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14230162',
            'jurusan' => 'Sistem Informasi Bisnis',
            'line_id' => 'kenenza.line',
            'whatsapp' => '6281234567898',
            'role' => 'user',
        ]);

        User::create([
            'name' => 'Steven Oentoro',
            'email' => 'steven@example.com',
            'password' => Hash::make('password'),
            'nrp' => 'C14220127',
            'jurusan' => 'Informatika',
            'line_id' => 'steven.line',
            'whatsapp' => '6281234567899',
            'role' => 'user',
        ]);
    }
}
