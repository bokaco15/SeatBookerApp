<?php

use App\Models\Event;
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
        Schema::create(Event::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained()->onDelete('cascade');

            $table->string('name');
            $table->string('type'); //enum
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->nullable();

            $table->string('status'); //enum

            $table->timestamps();

            $table->index(['hall_id', 'starts_at']);
            $table->index(['hall_id', 'ends_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Event::TABLE_NAME);
    }
};
