<?php

namespace Database\Seeders;

use App\Models\Lab;
use Illuminate\Database\Seeder;

class LabSeeder extends Seeder
{
    public function run(): void
    {
        $labs = [
            [
                'nama'      => 'Lab ASJ',
                'kode'      => 'ASJ',
                'kapasitas' => 36,
                'deskripsi' => 'Laboratorium Administrasi Server Jaringan. Dilengkapi dengan server rack, perangkat jaringan, dan workstation.',
            ],
            [
                'nama'      => 'Lab Bootcamp',
                'kode'      => 'BOOT',
                'kapasitas' => 30,
                'deskripsi' => 'Laboratorium Bootcamp untuk pelatihan intensif pemrograman dan pengembangan aplikasi.',
            ],
            [
                'nama'      => 'Lab FO',
                'kode'      => 'FO',
                'kapasitas' => 24,
                'deskripsi' => 'Laboratorium Fiber Optik. Tersedia peralatan splicing, pengukur, dan media fiber optik.',
            ],
            [
                'nama'      => 'Lab KJD',
                'kode'      => 'KJD',
                'kapasitas' => 36,
                'deskripsi' => 'Laboratorium Komputer Jaringan Dasar. Dilengkapi dengan komputer dan perangkat jaringan dasar.',
            ],
            [
                'nama'      => 'Lab AIJ',
                'kode'      => 'AIJ',
                'kapasitas' => 32,
                'deskripsi' => 'Laboratorium Administrasi Infrastruktur Jaringan. Tersedia peralatan jaringan enterprise.',
            ],
            [
                'nama'      => 'Lab IoT',
                'kode'      => 'IOT',
                'kapasitas' => 20,
                'deskripsi' => 'Laboratorium Internet of Things. Dilengkapi dengan Arduino, Raspberry Pi, sensor, dan aktuator.',
            ],
            [
                'nama'      => 'Lab Admin',
                'kode'      => 'ADM',
                'kapasitas' => 30,
                'deskripsi' => 'Laboratorium Administrasi Perkantoran. Tersedia komputer dan perangkat perkantoran.',
            ],
            [
                'nama'      => 'Lab Desain',
                'kode'      => 'DES',
                'kapasitas' => 28,
                'deskripsi' => 'Laboratorium Desain Grafis. Dilengkapi dengan komputer berspesifikasi tinggi dan tablet grafis.',
            ],
        ];

        foreach ($labs as $lab) {
            Lab::create(array_merge($lab, ['is_active' => true]));
        }
    }
}
