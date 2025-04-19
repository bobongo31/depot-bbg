<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute les migrations.
     */
    public function up(): void
    {
        Schema::create('dossiers_personnels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // propriétaire du compte GIC
            $table->unsignedBigInteger('agent_id'); // employé concerné
            $table->string('poste');
            $table->date('date_embauche')->nullable();
            $table->string('matricule')->nullable();
            $table->string('contrat_type')->nullable(); // CDI, CDD, stage...
            $table->text('notes')->nullable();
            $table->timestamps();
        
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Annule les migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dossiers_personnels');
    }
};
