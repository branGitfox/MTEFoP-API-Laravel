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
        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->string('provenance');
            $table->string('chrono');
            $table->string('ref');
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->foreignId('dir_id')->constrained('dirs', 'id')->cascadeOnDelete();
            $table->string('motif');
            $table->text('caracteristique');
            $table->string('propr');
            $table->enum('status', ['reçu', 'non reçu']);


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }
};
