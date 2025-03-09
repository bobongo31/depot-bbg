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
        Schema::create('courriers', function (Blueprint $table) {
            $table->id();
            $table->date('date_reception')->nullable(); // Date de réception du courrier
            $table->string('numero_enregistrement')->unique(); // Numéro d'enregistrement unique
            $table->string('expediteur'); // Expéditeur
            $table->string('objet'); // Objet du courrier
            $table->text('contenu'); // Contenu du courrier
            $table->string('fichier')->nullable(); // Fichier associé au courrier (s'il y en a)
            $table->enum('status', ['reçu', 'en attente', 'validé', 'traité']); // Statut du courrier
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relation avec l'utilisateur qui a reçu le courrier (utilisateur lié)
            $table->text('annotations')->nullable(); // Annotations supplémentaires
            $table->foreignId('validated_by')->nullable()->constrained('users')->onDelete('set null'); // Utilisateur ayant validé le courrier
            $table->text('validation_comment')->nullable(); // Commentaire de validation
            $table->timestamp('validation_date')->nullable(); // Date de validation du courrier
            $table->boolean('transmis_a_directeur')->default(false); // Indication si le courrier a été transmis au directeur
            $table->text('reponse_directeur')->nullable(); // Réponse du directeur
            $table->timestamps(); // Timestamps pour created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courriers');
    }
};
