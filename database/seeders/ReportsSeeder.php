<?php

namespace Database\Seeders;

use App\Models\Reports;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ReportsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startDate = Carbon::create(2025, 8, 15);
        $endDate = Carbon::now();

        // Batas geografis Kabupaten Jember
        $jemberBounds = [
            'min_lat' => -8.50,
            'max_lat' => -7.90,
            'min_lng' => 113.40,
            'max_lng' => 114.00
        ];

        // Titik pusat Jember
        $baseLat = -8.172367;
        $baseLng = 113.700592;

        for ($userId = 1; $userId <= 9; $userId++) {
            $date = $startDate->copy();

            while ($date <= $endDate) {
                $baseCategory = rand(1, 12);

                // Variasi kecil di sekitar titik pusat
                $baseLat2 = $this->randomInRange($baseLat - 0.02, $baseLat + 0.02);
                $baseLng2 = $this->randomInRange($baseLng - 0.02, $baseLng + 0.02);

                for ($i = 0; $i < 5; $i++) {
                    if ($i < 2) {
                        // Lokasi di sekitar pusat kota
                        $lat = $this->randomInRange($baseLat2 - 0.005, $baseLat2 + 0.005);
                        $lng = $this->randomInRange($baseLng2 - 0.005, $baseLng2 + 0.005);
                        $categoryId = $baseCategory;
                    } else {
                        // Lokasi acak di seluruh Kabupaten Jember
                        $lat = $this->randomInRange($jemberBounds['min_lat'], $jemberBounds['max_lat']);
                        $lng = $this->randomInRange($jemberBounds['min_lng'], $jemberBounds['max_lng']);
                        $categoryId = rand(1, 12);
                    }

                    $addressData = $this->getAddressDataFromLatLong($lat, $lng);

                    // Bersihkan nama kecamatan dari awalan
                    if (isset($addressData['subdistrict'])) {
                        $addressData['subdistrict'] = $this->cleanSubdistrictName($addressData['subdistrict']);
                    }

                    // Pastikan alamat mengandung "Jember"
                    if ($addressData['address'] && stripos($addressData['address'], 'Jember') === false) {
                        $addressData['address'] = "Lokasi di Kabupaten Jember, Jawa Timur";
                        $addressData['subdistrict'] = $this->cleanSubdistrictName($this->randomJemberSubdistrict());
                    }


                    $existingToday = Reports::whereDate('created_at', $date->toDateString())->count();
                    $nomorUrut = $existingToday + ($i + 1);

                    $kdReport = sprintf(
                        "Laporan/%s/%s/%s/U%02d/%04d",
                        $date->format('Y'),
                        $date->format('m'),
                        $date->format('d'),
                        $userId,
                        $nomorUrut
                    );


                    Reports::factory()->create([
                        'user_id' => $userId,
                        'id_category' => $categoryId,
                        'latitude' => $lat,
                        'longitude' => $lng,
                        'address' => $addressData['address'] ?? "Lokasi di Kabupaten Jember",
                        'subdistrict' => $addressData['subdistrict'] ?? $this->cleanSubdistrictName($this->randomJemberSubdistrict()),
                        'created_at' => $date->copy()->addSeconds($i * 360),
                        'updated_at' => $date->copy()->addSeconds($i * 360),
                        'kd_report' => $kdReport,
                    ]);


                    // Delay untuk menghindari rate limit Google API
                    usleep(200000); // 200ms
                }

                $date->addDay();
            }
        }
    }

    /**
     * Membersihkan nama kecamatan dari awalan
     */
    private function cleanSubdistrictName(string $name): string
    {
        // Hapus awalan "Kecamatan", "Kec.", dll
        $cleanName = preg_replace('/^(Kecamatan|Kec\.?)\s*/i', '', $name);

        // Hapus spasi berlebihan
        $cleanName = trim($cleanName);

        // Untuk Kecamatan Kaliwates, simpan hanya "Kaliwates"
        if (stripos($cleanName, 'Kaliwates') !== false) {
            return 'Kaliwates';
        }

        return $cleanName;
    }

    /**
     * Generate random number in range
     */
    private function randomInRange($min, $max)
    {
        return $min + mt_rand() / mt_getrandmax() * ($max - $min);
    }

    /**
     * Daftar kecamatan di Jember (tanpa awalan)
     */
    private function randomJemberSubdistrict(): string
    {
        $subdistricts = [
            'Ajung',
            'Ambulu',
            'Arjasa',
            'Balung',
            'Bangsalsari',
            'Gumukmas',
            'Jelbuk',
            'Jenggawah',
            'Jombang',
            'Kalisat',
            'Kaliwates',
            'Kencong',
            'Ledokombo',
            'Mayang',
            'Mumbulsari',
            'Pakusari',
            'Panti',
            'Patrang',
            'Puger',
            'Rambipuji',
            'Semboro',
            'Silo',
            'Sukorambi',
            'Sukowono',
            'Sumberbaru',
            'Sumberjambe',
            'Sumbersari',
            'Tanggul',
            'Tempurejo',
            'Umbulsari',
            'Wuluhan'
        ];

        return $subdistricts[array_rand($subdistricts)];
    }

    /**
     * Dapatkan data alamat dan kecamatan dari koordinat
     */
    private function getAddressDataFromLatLong($lat, $lng): array
    {
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        // Jika tidak ada API key, return default
        if (!$apiKey) {
            return [
                'address' => "Lokasi di Kabupaten Jember",
                'subdistrict' => $this->cleanSubdistrictName($this->randomJemberSubdistrict())
            ];
        }

        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng={$lat},{$lng}&key={$apiKey}&language=id";

        try {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                if ($data['status'] === 'OK' && isset($data['results'][0])) {
                    $result = $data['results'][0];

                    return [
                        'address' => $result['formatted_address'] ?? null,
                        'subdistrict' => $this->extractSubdistrict($result['address_components'])
                    ];
                }
            }
        } catch (\Exception $e) {
            // Log error jika diperlukan
        }

        return [
            'address' => "Lokasi di Kabupaten Jember",
            'subdistrict' => $this->cleanSubdistrictName($this->randomJemberSubdistrict())
        ];
    }

    /**
     * Ekstrak nama kecamatan dari komponen alamat
     */
    private function extractSubdistrict(array $addressComponents): ?string
    {
        // Cari komponen dengan tipe 'administrative_area_level_3' (kecamatan)
        foreach ($addressComponents as $component) {
            if (in_array('administrative_area_level_3', $component['types'])) {
                return $component['long_name'];
            }
        }

        // Cari komponen dengan tipe 'sublocality_level_1' atau 'sublocality'
        foreach ($addressComponents as $component) {
            if (
                in_array('sublocality_level_1', $component['types']) ||
                in_array('sublocality', $component['types'])
            ) {
                return $component['long_name'];
            }
        }

        // Cari manual dari alamat lengkap
        foreach ($addressComponents as $component) {
            if (
                in_array('administrative_area_level_2', $component['types']) &&
                stripos($component['long_name'], 'kecamatan') !== false
            ) {
                return $component['long_name'];
            }
        }

        return null;
    }
}
