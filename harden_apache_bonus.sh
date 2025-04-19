#!/bin/bash

echo "🛡️ Démarrage du hardening bonus Apache..."

echo "✏️ Configuration des en-têtes de sécurité..."

# Ajout des en-têtes dans la config globale
SECURITY_CONF="/etc/apache2/conf-available/security.conf"

sudo sed -i 's/^ServerTokens .*/ServerTokens Prod/' $SECURITY_CONF
sudo sed -i 's/^ServerSignature .*/ServerSignature Off/' $SECURITY_CONF

# Ajout de nouvelles directives si elles ne sont pas déjà présentes
grep -q "Header set Content-Security-Policy" $SECURITY_CONF || echo 'Header set Content-Security-Policy "default-src '\''self'\'';"' | sudo tee -a $SECURITY_CONF
grep -q "Header always set Referrer-Policy" $SECURITY_CONF || echo 'Header always set Referrer-Policy "strict-origin-when-cross-origin"' | sudo tee -a $SECURITY_CONF
grep -q "Header always set X-Permitted-Cross-Domain-Policies" $SECURITY_CONF || echo 'Header always set X-Permitted-Cross-Domain-Policies "none"' | sudo tee -a $SECURITY_CONF

# Active le module headers si pas encore fait
sudo a2enmod headers 2>/dev/null

echo "🔁 Redémarrage d’Apache..."
sudo service apache2 restart

echo "✅ Hardening bonus Apache terminé avec succès !"
