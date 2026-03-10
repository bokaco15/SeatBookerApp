<?php

use App\Models\Hall;
use App\Models\Seat;
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
        Schema::create(Seat::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->onDelete('cascade');
            $table->integer('row');
            $table->integer('number');
            $table->foreignId('seat_category_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['hall_id', 'row', 'number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Seat::TABLE_NAME);
    }
};
