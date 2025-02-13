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
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id('m_id');
            $table->string('ref_initial');
            $table->string('ref_propre');
            $table->string('id_dg');
            $table->foreignId('courrier_id')->constrained('courriers', 'c_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->enum('type', ['transfert', 'recuperation']);
            $table->enum('status', ['reçu', 'non reçu']);
            $table->string('serv_id');
            $table->string('description');
            $table->enum('transfere', ['non', 'oui']);
            $table->integer('current_trans_id');
            $table->integer('current_trans_id_dir');
            $table->string('propr')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mouvement');
    }
};
