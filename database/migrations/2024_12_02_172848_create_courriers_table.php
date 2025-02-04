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
            $table->id('c_id');
            $table->string('provenance');
            $table->string('chrono');
            $table->string('cin');
            $table->string('tel');
            $table->string('ref');
            $table->foreignId('user_id')->constrained('users','id');
            $table->foreignId('dir_id')->constrained('dirs','d_id')->cascadeOnDelete();
            $table->string('motif');
            $table->text('caracteristique');
            $table->string('proprietaire');
            $table->enum('status', ['reçu', 'non reçu']);
            $table->enum('transfere', ['oui', 'non']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations (delete).
     */
    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }
};
