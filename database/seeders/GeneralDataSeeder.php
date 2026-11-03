<?php

namespace Database\Seeders;

use App\Models\GeneralData;
use Illuminate\Database\Seeder;

class GeneralDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            // 1. Jenis Kendaraan -> group_: product_type
            'product_type' => [
                'Kendaraan Komersial (CV - Commercial Vehicle)',
                'Kendaraan Penumpang / Light Commercial Vehicle (LCV)',
            ],
            // 2. Kategori -> group_: product_category
            'product_category' => [
                'Kendaraan Penumpang / SUV & Double Cabin (MU-X, D-Max)',
                'Truk Ringan / Light Truck (Elf / N-Series)',
                'Truk Sedang / Medium Truck (Giga / F-Series)',
                'Truk Berat / Tractor Head (Giga Tractor Head / C&E Series)',
                'Bus / Microbus (Elf Bus, Traga Bus)',
                'Pick-up / Logistik Ringan (Traga)',
            ],
            // 3. Tipe Karoseri -> group_: product_application
            'product_application' => [
                'Box (Besi/Aluminium)',
                'Bak (Besi/Kayu/Drop Side)',
                'Dump Truck',
                'Tangki',
                'Wingbox',
                'Flat Deck / Logging',
                'Microbus / Ambulance, Crane / Towing, Garbage / Compactor',
            ],
            // 4. Konfigurasi Roda Penggerak -> group_: wheel_configuration
            'wheel_configuration' => [
                '4 Roda (4x2 / Engkel)',
                '6 Roda (4x2)',
                '6x2 (Tronton banci)',
                '6x4 (Tronton Heavy Duty / Off-road)',
                '4x4 (Rigid / Double Cabin)',
            ],
            // 5. Bobot Muatan -> group_: load_capacity
            'load_capacity' => [
                '5 – 10 Ton',
                '10 – 16 Ton',
                '> 16 Ton',
            ],
            // 6. Panjang Sasis -> group_: wheelbase
            'wheelbase' => [
                'Pendek (Short Wheelbase / SWB)',
                'Sedang (Medium Wheelbase / MWB)',
                'Panjang (Long Wheelbase / LWB / Extra Long)',
            ],
            // 7. Rentang Tenaga -> group_: horse_power
            'horse_power' => [
                '100 – 150 PS',
                '150 – 240 PS',
                '240 – 285 PS',
                '300+ PS',
            ],
            // 8. Kapasitas Penumpang -> group_: seating_capacity
            'seating_capacity' => [
                '2 Seater',
                '5 Seater',
                '7 Seater (MU-X)',
            ],
        ];

        foreach ($items as $group => $lists) {
            GeneralData::where('group_', $group)->delete();

            $i = 1;
            foreach ($lists as $val) {
                GeneralData::create([
                    'group_' => $group,
                    'code' => $group . '_' . $i,
                    'sort_number' => $i,
                    'list_data' => $val,
                    'is_active' => true,
                ]);
                $i++;
            }
        }
    }
}
