<?php

namespace Database\Seeders;

use App\Models\DisasterCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Abrasi', 'type' => 'kejadian_bencana'],
            ['name' => 'Banjir', 'type' => 'kejadian_bencana'],
            ['name' => 'Angin Kencang', 'type' => 'kejadian_bencana'],
            ['name' => 'Gempa Bumi', 'type' => 'kejadian_bencana'],
            ['name' => 'Erupsi Gunung Api', 'type' => 'kejadian_bencana'],
            ['name' => 'Kebakaran Hutan dan Lahan', 'type' => 'kejadian_bencana'],
            ['name' => 'Kebakaran', 'type' => 'kejadian_bencana'],
            ['name' => 'Kekeringan', 'type' => 'kejadian_bencana'],
            ['name' => 'Tanah Longsor', 'type' => 'kejadian_bencana'],
            ['name' => 'Laka Air', 'type' => 'kejadian_musibah'],
            ['name' => 'Kebakaran Rumah', 'type' => 'kejadian_musibah'],
            ['name' => 'Rumah Roboh', 'type' => 'kejadian_musibah'],
            ['name' => 'Pohon Tumbang', 'type' => 'kejadian_musibah'],
            ['name' => 'Kecelakaan Kerja', 'type' => 'kejadian_musibah'],
            ['name' => 'Orang Tersambar Petir', 'type' => 'kejadian_musibah'],
            ['name' => 'Rumah Tersambar Petir', 'type' => 'kejadian_musibah'],
            ['name' => 'Fasilitas Umum Rusak', 'type' => 'kejadian_musibah'],
        ];

        foreach ($categories as $category) {
            DisasterCategory::create($category);
        }


    }
}
