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
        Schema::table('reservations', function (Blueprint $table) {
            // 1. HAPUS FOREIGN KEY CONSTRAINT DULU
            $table->dropForeign(['table_id']);
            
            // 2. Baru hapus kolom table_id
            $table->dropColumn('table_id');
            
            // 3. Tambah kolom baru
            $table->string('table_numbers')->nullable()->after('guest_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            // 1. Tambah kembali kolom table_id
            $table->foreignId('table_id')->nullable()->after('guest_count');
            
            // 2. Drop kolom baru
            $table->dropColumn('table_numbers');
        });
    }
};
