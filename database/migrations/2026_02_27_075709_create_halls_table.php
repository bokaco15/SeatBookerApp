<?php

use App\Models\Hall;
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
        Schema::create(Hall::TABLE_NAME, function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->index();
            $table->unsignedSmallInteger('rows');
            $table->unsignedSmallInteger('columns');
            $table->boolean('is_active')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(Hall::TABLE_NAME);
    }
};
