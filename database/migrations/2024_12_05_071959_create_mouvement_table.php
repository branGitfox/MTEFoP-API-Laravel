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
            $table->foreignId('id_dg')->constrained('dgs', 'dg_id')->cascadeOnDelete();
            $table->foreignId('courrier_id')->constrained('courriers', 'c_id')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->enum('type', ['transfert', 'recuperation']);
            $table->enum('status', ['reçu', 'non reçu']);
            $table->foreignId('serv_id')->constrained('servs', 's_id')->cascadeOnDelete();
            $table->string('description');
            $table->enum('transfere', ['non', 'oui']);
            $table->integer('current_trans_id');
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
