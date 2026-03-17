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
        Schema::create('annexes_dossier_personnel', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dossier_personnel_id')->constrained('dossiers_personnels')->onDelete('cascade');
            $table->string('path');
            $table->timestamps();
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('annexes_dossier_personnel');
    }
};
