<?php

use App\Models\Booking;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(Booking::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete(); //foreign id
            $table->string('email')->nullable();
            $table->string('status')->index(); //enum: pending, confirmed, cancelled, expired
            $table->timestamp('expires_at')->nullable();
            $table->integer('total_amount')->nullable();
            $table->string('session_id')->nullable();
            $table->string('browser_session_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Booking::TABLE_NAME);
    }
};
