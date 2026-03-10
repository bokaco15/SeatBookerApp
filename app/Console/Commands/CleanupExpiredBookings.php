<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CleanupExpiredBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-expired-bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Koristimo whereIn da uhvatimo i pending i unpaid statuse
        $expiredBookings = Booking::whereIn('status', ['pending', 'unpaid'])
            ->where('expires_at', '<', now())
            ->with('booking_items')
            ->get();

        if ($expiredBookings->isEmpty()) {
            $this->info('Nema isteklih rezervacija.');
            return;
        }

        foreach ($expiredBookings as $booking) {
            \DB::transaction(function () use ($booking) {
                $booking->delete();
            });

            $this->info("Rezervacija ID: {$booking->id} je uspešno poništena.");
        }

        $this->info('Čišćenje završeno.');
    }
}
