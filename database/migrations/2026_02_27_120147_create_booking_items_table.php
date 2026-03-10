<?php

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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete(); //foreign id
            $table->foreignId('event_id')->constrained()->cascadeOnDelete(); //foreign id
            $table->foreignId('seat_id')->constrained()->cascadeOnDelete(); //FID
            $table->foreignId('seat_category_id')->constrained()->cascadeOnDelete(); //fid
            $table->integer('price');
            $table->string('status'); //enum
            $table->uuid('qr_token')->nullable()->unique();
            $table->timestamp('qr_sent_at')->nullable();
            $table->timestamp('qr_used_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};
