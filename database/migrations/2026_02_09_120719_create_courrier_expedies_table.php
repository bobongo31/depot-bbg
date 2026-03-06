<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('courrier_expedies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('numero_ordre');        // N° manuel
            $table->date('date_expedition');
            $table->string('numero_lettre')->unique();
            $table->string('destinataire');
            $table->text('resume');
            $table->text('observation')->nullable();

            // ANNEXES DANS LA MEME TABLE
            $table->json('annexes')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courrier_expedies');
    }
};
