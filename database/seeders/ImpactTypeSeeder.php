<?php

namespace Database\Seeders;

use App\Models\ImpactType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ImpactTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $impactTypes = [
            'lightly_damaged_houses',
            'moderately_damaged_houses',
            'heavily_damaged_houses',
            'damaged_public_facilities',
            'missing_people',
            'injured_people',
            'affected_people',
            'deceased_people',
        ];

        foreach ($impactTypes as $type) {
            ImpactType::create([
                'name' => $type,
            ]);
        }
    }
}
