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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->onUpdate('cascade'); // ID pengguna yang membuat reservasi
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade')->onUpdate('cascade'); // ID meja yang dipesan
            $table->date('reservation_date'); // Tanggal reservasi
            $table->time('reservation_time'); // Waktu reservasi
            $table->unsignedTinyInteger('guest_count'); // Jumlah tamu
            $table->text('notes')->nullable(); // Catatan tambahan untuk reservasi
            $table->foreignId('promo_id')->nullable()->constrained('promos')->onDelete('set null')->onUpdate('cascade'); // relasi promo
            $table->decimal('total_DP', 10, 2);
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'expired'])->default('pending'); // Status reservasi
            $table->timestamps();
        });
    }   

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
