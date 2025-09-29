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
        Schema::create('promos', function (Blueprint $table) {
            $table->id();
            $table->string('promo_code');
            $table->string('description')->nullable();
            $table->decimal('discount', 10, 2)->default(0); //nilai diskon
            $table->enum('type', ['percent', 'fixed'])->default('percent'); // jenis diskon: persen atau nominal
            $table->date('start_date')->nullable(); // mulai berlaku
            $table->date('end_date')->nullable();   // berakhir
            $table->unsignedInteger('usage_limit')->nullable(); // batas penggunaan
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promos');
    }
};
