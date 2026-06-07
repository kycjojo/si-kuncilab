<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@labsekolah.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);

        // Pengawas
        User::create([
            'name'     => 'Budi Santoso',
            'email'    => 'pengawas@labsekolah.com',
            'password' => Hash::make('password'),
            'role'     => 'pengawas',
        ]);

        User::create([
            'name'     => 'Siti Rahayu',
            'email'    => 'pengawas2@labsekolah.com',
            'password' => Hash::make('password'),
            'role'     => 'pengawas',
        ]);

        // Murid
        $murid = [
            ['name' => 'Ahmad Fauzi',    'email' => 'ahmad@labsekolah.com',    'nis' => '2024001', 'kelas' => 'XII TKJ 1'],
            ['name' => 'Dewi Lestari',   'email' => 'dewi@labsekolah.com',     'nis' => '2024002', 'kelas' => 'XII TKJ 1'],
            ['name' => 'Rizky Pratama',  'email' => 'rizky@labsekolah.com',    'nis' => '2024003', 'kelas' => 'XII TKJ 2'],
            ['name' => 'Nur Fadilah',    'email' => 'nurfadilah@labsekolah.com','nis' => '2024004', 'kelas' => 'XI TKJ 1'],
            ['name' => 'Bagas Setiawan', 'email' => 'bagas@labsekolah.com',    'nis' => '2024005', 'kelas' => 'XI TKJ 2'],
        ];

        foreach ($murid as $m) {
            User::create(array_merge($m, [
                'password' => Hash::make('password'),
                'role'     => 'murid',
            ]));
        }
    }
}
