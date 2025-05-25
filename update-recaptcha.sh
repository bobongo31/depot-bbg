#!/bin/bash

# Clés reCAPTCHA v2 (à personnaliser)
SITE_KEY="6Ldo-UgrAAAAAKpSCibRDAe2I1W7Nyx7meOJn01P"
SECRET_KEY="6Ldo-UgrAAAAAGd1CG_f7taMXDyNEYOrIpx-t3qq"

# Chemin vers le fichier .env
ENV_FILE=".env"

# Vérifie si le fichier .env existe
if [ ! -f "$ENV_FILE" ]; then
    echo "Erreur : fichier $ENV_FILE introuvable."
    exit 1
fi

# Met à jour ou ajoute les variables dans le fichier .env
echo "🔄 Mise à jour des clés reCAPTCHA dans $ENV_FILE..."

# Supprime les anciennes lignes si elles existent
sed -i '/^NOCAPTCHA_SITEKEY=/d' "$ENV_FILE"
sed -i '/^NOCAPTCHA_SECRET=/d' "$ENV_FILE"

# Ajoute les nouvelles clés à la fin du fichier
echo "NOCAPTCHA_SITEKEY=$SITE_KEY" >> "$ENV_FILE"
echo "NOCAPTCHA_SECRET=$SECRET_KEY" >> "$ENV_FILE"

# Vide et regénère le cache de configuration Laravel
echo "🧹 Nettoyage du cache Laravel..."
php artisan config:clear
php artisan config:cache

echo "✅ Clés mises à jour et cache régénéré."
