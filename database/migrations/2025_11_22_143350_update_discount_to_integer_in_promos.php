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
        Schema::table('promos', function (Blueprint $table) {
            // Hapus kolom type jika masih ada
            // if (Schema::hasColumn('promos', 'type')) {
            //     $table->dropColumn('type');
            // }

            // Ubah kolom discount menjadi integer
            $table->integer('discount')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            // Kembalikan kolom type
            // $table->enum('type', ['percent', 'fixed'])->default('percent');

            // Kembalikan kolom discount ke decimal
            $table->decimal('discount', 5, 2)->change();
        });
    }
};
