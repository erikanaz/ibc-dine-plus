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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Nomor meja, harus unik
            $table->integer('capacity'); // Kapasitas meja
            $table->enum('status', ['available', 'occupied', 'reserved', 'maintenance'])->default('available'); // Status meja
            $table->enum('location', ['indoor', 'outdoor'])->default('indoor');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};
