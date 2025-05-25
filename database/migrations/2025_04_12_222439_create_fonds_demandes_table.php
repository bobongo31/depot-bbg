<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFondsDemandesTable extends Migration
{
    public function up()
    {
        Schema::create('fonds_demandes', function (Blueprint $table) {
            $table->id();
            // Relation avec l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Montant de la demande
            $table->decimal('montant', 15, 2);
            // Motif de la demande
            $table->string('motif');
            // Statut de la demande
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            // Timestamps pour la création et la mise à jour
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fonds_demandes');
    }
}
