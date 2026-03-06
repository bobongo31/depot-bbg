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
            Schema::create('courrier_expedie_copies', function (Blueprint $table) {
        $table->id();
        $table->foreignId('courrier_expedie_id')->constrained()->cascadeOnDelete();

        $table->string('direction');
        $table->string('service');

        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courrier_expedie_copies');
    }
};
