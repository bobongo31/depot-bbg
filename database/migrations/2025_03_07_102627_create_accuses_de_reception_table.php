<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('accuse_receptions', function (Blueprint $table) {
            $table->id();
            $table->string('numero_enregistrement')->unique();
            $table->date('date_accuse_reception');
            $table->date('date_reception');
            $table->string('numero_reference')->nullable();
            $table->string('nom_expediteur');
            $table->text('resume');
            $table->text('observation')->nullable();
            $table->text('commentaires')->nullable();
            $table->enum('statut', ['reçu', 'en attente', 'traité'])->default('reçu');
            $table->timestamps();
        });

        Schema::create('annexes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('accuse_reception_id')->constrained()->onDelete('cascade');
            $table->string('file_path');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('annexes');
        Schema::dropIfExists('accuse_receptions');
    }
};
