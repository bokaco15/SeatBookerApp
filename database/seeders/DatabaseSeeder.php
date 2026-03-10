<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Borislav Ilic',
            'email' => 'bborislavilic@gmail.com',
            'role' => 'admin',
            'password' => Hash::make('bokaco123'),
        ]);

        $this->call([
            HallSeeder::class,
            SeatCategorySeeder::class,
            SeatSeeder::class,
            EventSeeder::class,
            BookingSeeder::class,
        ]);
    }
}
