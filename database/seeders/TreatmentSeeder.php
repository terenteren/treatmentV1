<?php

namespace Database\Seeders;

use App\Models\Treatment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TreatmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $treatments = [
            'ABA',
            '언어치료',
            '감통통합치료',
            '놀이치료',
            '작업치료',
            '음악치료',
            '미술치료',
            '특수체육',
            'PCIT',
            'Floor Time',
            '기타(직접기록)',
        ];

        foreach ($treatments as $treatment) {
            Treatment::updateOrCreate(['name' => $treatment]);
        }
    }
}
