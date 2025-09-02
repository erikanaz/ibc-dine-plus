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
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role_id')) {
                // Coba hapus foreign key jika masih ada
                try {
                    $table->dropForeign(['role_id']);
                } catch (\Throwable $e) {
                    // Lewatkan jika constraint sudah tidak ada
                }

                // Hapus kolom
                $table->dropColumn('role_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()
                ->constrained('roles')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }
};
