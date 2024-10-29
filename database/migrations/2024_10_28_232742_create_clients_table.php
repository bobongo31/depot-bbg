<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('nom_redevable');
            $table->string('adresse');
            $table->string('telephone');
            $table->string('nom_taxateur');
            $table->string('nom_liquidateur');
            $table->decimal('matiere_taxable', 10, 2);
            $table->decimal('prix_a_payer', 10, 2)->virtualAs('matiere_taxable * 0.05'); // Calcul automatique
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
