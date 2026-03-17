#!/bin/bash

echo "🔐 Démarrage du hardening Apache + Laravel..."

APACHE_CONF="/etc/apache2/conf-available/security.conf"
SSL_CONF="/etc/apache2/sites-available/default-ssl.conf"
HTACCESS="/var/www/html/.htaccess"
LARAVEL_DIR="/var/www/html/gic/gestion-courrier"

# 1. 🔐 Activer HSTS + désactiver ServerTokens et Signature
echo "✏️ Renforcement de la config Apache..."

sudo sed -i 's/^ServerTokens .*/ServerTokens Prod/' $APACHE_CONF
sudo sed -i 's/^ServerSignature .*/ServerSignature Off/' $APACHE_CONF

# Activer Headers
sudo a2enmod headers

# Ajout des headers si non déjà présents
sudo grep -q "Strict-Transport-Security" $APACHE_CONF || echo 'Header always set Strict-Transport-Security "max-age=63072000; includeSubDomains; preload"' | sudo tee -a $APACHE_CONF

# 2. 🚫 Bloquer les méthodes HTTP dangereuses
sudo grep -q "TraceEnable" $APACHE_CONF || echo "TraceEnable Off" | sudo tee -a $APACHE_CONF

# 3. 🔒 Forcer SSL et sécuriser .htaccess
echo "📁 Mise à jour du fichier .htaccess..."
cat <<EOF | sudo tee $HTACCESS > /dev/null
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{HTTPS} !=on
    RewriteRule ^ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
</IfModule>

<IfModule mod_headers.c>
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-Content-Type-Options "nosniff"
    Header always set Referrer-Policy "strict-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=()"
</IfModule>

# 🔐 Bloquer les fichiers sensibles
<FilesMatch "(\.env|\.git|composer\.json|composer\.lock|artisan|server\.php|readme\.md|license)">
    Order allow,deny
    Deny from all
</FilesMatch>
EOF

# 4. 🔄 Redémarrage d’Apache
echo "🔄 Redémarrage d’Apache..."
sudo systemctl reload apache2

# 5. 🧼 Cache Laravel
if [ -d "$LARAVEL_DIR" ]; then
    echo "🧹 Vidage du cache Laravel..."
    cd $LARAVEL_DIR
    php artisan config:clear
    php artisan route:clear
    php artisan cache:clear
    php artisan view:clear
fi

echo "✅ Hardening Apache + Laravel terminé avec succès !"
