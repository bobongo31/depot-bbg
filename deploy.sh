#!/bin/bash

# Aller dans le dossier de ton projet Laravel
cd /var/www/html/gic/gestion-courrier || {
    echo "❌ Erreur : dossier non trouvé."
    exit 1
}

echo "📥 Pull des modifications depuis GitHub..."
git pull origin develop || {
    echo "❌ Échec du git pull."
    exit 1
}

echo "📦 Installation des dépendances PHP (production)..."
composer install --no-dev --optimize-autoloader || {
    echo "❌ Échec du composer install."
    exit 1
}

echo "🧼 Nettoyage des caches Laravel..."
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo "⚡ Mise en cache..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Déploiement terminé sans exécuter les migrations !"
