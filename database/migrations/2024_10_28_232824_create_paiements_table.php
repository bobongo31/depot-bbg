<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaiementsTable extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->string('matiere_taxable');
            $table->decimal('prix_de_la_matiere', 10, 2);
            $table->decimal('prix_a_payer', 10, 2)->virtualAs('prix_de_la_matiere * 0.05'); // Calcul automatique
            $table->date('date_ordonancement');
            $table->date('date_accuse_reception');
            $table->decimal('cout_opportunite', 10, 2);
            $table->date('date_de_paiement');
            $table->boolean('retard_de_paiement')->default(false);
            $table->string('status'); // Exemple: en attente, validé, annulé
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
}

