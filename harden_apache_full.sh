#!/bin/bash

echo "🛡️ Démarrage du hardening complet Apache + Laravel..."

HTACCESS_PATH="/var/www/html/gic/gestion-courrier/public/.htaccess"

# Vérification de l'existence du fichier
if [ ! -f "$HTACCESS_PATH" ]; then
    echo "❌ Fichier .htaccess introuvable à $HTACCESS_PATH"
    exit 1
fi

echo "✏️ Configuration des en-têtes de sécurité dans .htaccess..."

# Ajout des en-têtes (si absents)
grep -q "Header always set Strict-Transport-Security" "$HTACCESS_PATH" || echo 'Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"' >> "$HTACCESS_PATH"
grep -q "Header always set X-Frame-Options" "$HTACCESS_PATH" || echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> "$HTACCESS_PATH"
grep -q "Header always set X-Content-Type-Options" "$HTACCESS_PATH" || echo 'Header always set X-Content-Type-Options "nosniff"' >> "$HTACCESS_PATH"
grep -q "Header always set Referrer-Policy" "$HTACCESS_PATH" || echo 'Header always set Referrer-Policy "strict-origin-when-cross-origin"' >> "$HTACCESS_PATH"
grep -q "Header always set X-Permitted-Cross-Domain-Policies" "$HTACCESS_PATH" || echo 'Header always set X-Permitted-Cross-Domain-Policies "none"' >> "$HTACCESS_PATH"
grep -q "Header always set Content-Security-Policy" "$HTACCESS_PATH" || cat <<EOT >> "$HTACCESS_PATH"
Header always set Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:; font-src 'self'; connect-src 'self'; frame-ancestors 'none'; base-uri 'self';"
EOT

# Encadrer par <IfModule> si absent
if ! grep -q "<IfModule mod_headers.c>" "$HTACCESS_PATH"; then
    sed -i '1s/^/<IfModule mod_headers.c>\n/' "$HTACCESS_PATH"
    echo "</IfModule>" >> "$HTACCESS_PATH"
fi

echo "🔁 Redémarrage d’Apache..."
sudo service apache2 restart

echo "🧹 Vidage des caches Laravel..."
cd /var/www/html/gic/gestion-courrier || exit
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Hardening complet Apache + Laravel terminé avec succès !"
