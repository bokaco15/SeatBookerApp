<?php

namespace Database\Seeders;

use App\Enums\Event\EventType;
use Faker\Factory;
use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Hall;
use Carbon\Carbon;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Factory::create();

        $hall = Hall::first();

        $startDate = Carbon::parse('2035-02-20 18:00:00');

        for ($i = 0; $i < 30; $i++) {
            $currentStart = $startDate->copy()->addDays($i);
            $currentEnd = $currentStart->copy()->addHours(2)->addMinutes(30);

            Event::create([
                'hall_id' => $hall->id,
                'name' => $faker->name,
                'type' => $faker->randomElement(EventType::cases()),
                'starts_at' => $currentStart,
                'ends_at' => $currentEnd,
                'status' => 'published',
            ]);
        }

    }
}
