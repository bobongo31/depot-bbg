<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReponsesTable extends Migration
{
    public function up()
    {
        Schema::create('reponses', function (Blueprint $table) {
            $table->id();
            $table->string('numero_enregistrement');
            $table->string('numero_reference');
            $table->string('service_concerne');
            $table->text('observation')->nullable();
            $table->text('commentaires')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reponses');
    }
}
