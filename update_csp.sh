#!/bin/bash

# Script de mise à jour de la Content-Security-Policy et Permissions-Policy pour Apache + Laravel

APACHE_CONF="/etc/apache2/sites-available/000-default.conf"

echo "🔐 Mise à jour de la Content-Security-Policy (CSP)..."

# Supprimer les anciennes directives CSP et Permissions-Policy
sudo sed -i '/Content-Security-Policy/d' "$APACHE_CONF"
sudo sed -i '/Permissions-Policy/d' "$APACHE_CONF"

# Ajouter la nouvelle directive CSP (moins stricte) + Permissions-Policy raisonnable
sudo bash -c "echo 'Header always set Content-Security-Policy \"default-src '\''self'\''; script-src '\''self'\'' '\''unsafe-inline'\'' '\''unsafe-eval'\'' http://127.0.0.1:5173 https://code.jquery.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; script-src-elem '\''self'\'' '\''unsafe-inline'\'' '\''unsafe-eval'\'' http://127.0.0.1:5173 https://code.jquery.com https://cdn.jsdelivr.net https://cdnjs.cloudflare.com; style-src '\''self'\'' '\''unsafe-inline'\'' https://fonts.googleapis.com https://cdn.jsdelivr.net; style-src-elem '\''self'\'' '\''unsafe-inline'\'' https://fonts.googleapis.com https://cdn.jsdelivr.net; font-src '\''self'\'' https://fonts.gstatic.com data:; img-src '\''self'\'' data: blob:; connect-src '\''self'\'' ws://127.0.0.1:5173 http://127.0.0.1:5173; frame-ancestors '\''none'\''; base-uri '\''self'\''\"' >> $APACHE_CONF"

sudo bash -c "echo 'Header always set Permissions-Policy \"accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()\"' >> $APACHE_CONF"

echo "🔄 Redémarrage d'Apache..."
sudo systemctl restart apache2

echo "🧹 Vidage des caches Laravel..."

php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

echo "✅ Mise à jour de la CSP et vidage des caches Laravel terminé avec succès !"
