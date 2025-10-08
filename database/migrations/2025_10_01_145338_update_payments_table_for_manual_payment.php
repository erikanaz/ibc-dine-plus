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
        Schema::table('payments', function (Blueprint $table) {
            // Hapus kolom yang terkait Midtrans
            $table->dropColumn(['transaction_id', 'payment_url']);
            
            // Tambah kolom baru untuk manual payment
            $table->enum('status', ['pending', 'verifying', 'paid', 'failed', 'expired'])->default('pending')->change();
            $table->enum('payment_type', ['dp', 'full', 'settlement'])->default('dp');
            $table->string('payment_proof')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Kembalikan kolom Midtrans
            $table->string('transaction_id')->nullable();
            $table->string('payment_url')->nullable();
            
            // Hapus kolom manual payment
            $table->dropColumn([
                'payment_type',
                'payment_proof', 
                'bank_name',
                'account_number',
                'account_name', 
                'notes',
                'paid_at',
                'verified_at',
                'verified_by'
            ]);
            
            // Kembalikan status ke nilai awal
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending')->change();
        });
    }
};