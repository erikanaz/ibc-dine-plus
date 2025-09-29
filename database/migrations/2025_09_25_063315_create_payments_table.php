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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservation_id')->constrained('reservations')->onDelete('cascade')->onUpdate('cascade'); 
            $table->decimal('amount', 10, 2); // jumlah pembayaran (DP atau pelunasan)
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending'); // status pembayaran
            $table->string('transaction_id')->nullable(); // ID transaksi dari Midtrans
            $table->string('payment_url')->nullable(); // URL pembayaran Midtrans
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
