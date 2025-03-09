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
        // Table des utilisateurs (users)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable(); // Date de vérification de l'email
            $table->string('password');
            $table->enum('role', ['agent', 'chef_service', 'directeur_general']); // Champ role
            $table->rememberToken(); // Token pour "remember me"
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        // Table des tokens de réinitialisation de mot de passe (password_reset_tokens)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // L'email est la clé primaire
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Table des sessions (sessions)
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // L'ID de session est la clé primaire
            $table->foreignId('user_id')->nullable()->index(); // ID de l'utilisateur lié à la session
            $table->string('ip_address', 45)->nullable(); // Adresse IP de l'utilisateur
            $table->text('user_agent')->nullable(); // User-agent du navigateur
            $table->longText('payload'); // Charge utile de la session
            $table->integer('last_activity')->index(); // Dernière activité (timestamp)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Suppression des tables créées lors du rollback
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
