<?php

namespace Database\Seeders;

use App\Models\SeatCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SeatCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SeatCategory::create([
            'name' => 'VIP',
            'price' => 20,
        ]);

        SeatCategory::create([
            'name' => 'Normal',
            'price' => 10,
        ]);
    }
}
