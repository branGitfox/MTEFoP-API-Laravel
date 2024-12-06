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
        Schema::create('servs', function (Blueprint $table) {
            $table->id('s_id');
            $table->string('nom_serv');
            $table->string('porte_serv');
            $table->foreignId('dir_id')->constrained('dirs', 'd_id')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('servs');
    }
};
