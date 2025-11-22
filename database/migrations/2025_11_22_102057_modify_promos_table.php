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
            // $table->dropColumn('type'); // hapus enum percent/fixed
            $table->decimal('discount', 5, 2)->change(); // ubah precision persen
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('promos', function (Blueprint $table) {
            // $table->enum('type', ['percent', 'fixed'])->default('percent');
            $table->decimal('discount', 10, 2)->change();
        });
    }
};
