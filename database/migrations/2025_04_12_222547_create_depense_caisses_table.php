<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepenseCaissesTable extends Migration
{
    public function up()
    {
        Schema::create('depense_caisses', function (Blueprint $table) {
            $table->id();
            // Relation avec l'utilisateur
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            // Rubrique de la dépense
            $table->string('rubrique');
            // Montant de la dépense
            $table->decimal('montant', 15, 2);
            // Date de la dépense
            $table->date('date_depense');
            // Description de la dépense (optionnelle)
            $table->text('description')->nullable();
            // Timestamps pour la création et la mise à jour
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('depense_caisses');
    }
}
