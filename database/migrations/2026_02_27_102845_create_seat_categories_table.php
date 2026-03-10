<?php

use App\Models\SeatCategory;
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
        Schema::create(SeatCategory::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name', 32);
            $table->integer('price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(SeatCategory::TABLE_NAME);
    }
};
