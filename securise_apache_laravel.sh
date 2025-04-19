#!/bin/bash

echo "🔐 Sécurisation d'Apache + Laravel en cours..."

# Activer le module headers
echo "✅ Activation du module headers..."
a2enmod headers

# Ajout des headers de sécurité dans la conf Apache
SECURITY_CONF="/etc/apache2/conf-available/security.conf"
VHOST_CONF="/etc/apache2/sites-available/000-default-le-ssl.conf"

# Sauvegarde avant modification
cp "$SECURITY_CONF" "${SECURITY_CONF}.bak"
cp "$VHOST_CONF" "${VHOST_CONF}.bak"

echo "✅ Configuration d'Apache..."

# Headers dans security.conf
sed -i 's/^ServerTokens .*/ServerTokens Prod/' "$SECURITY_CONF"
sed -i 's/^ServerSignature .*/ServerSignature Off/' "$SECURITY_CONF"

# Ajout dans le VirtualHost SSL
grep -q 'X-Frame-Options' "$VHOST_CONF" || echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> "$VHOST_CONF"
grep -q 'Strict-Transport-Security' "$VHOST_CONF" || echo 'Header always set Strict-Transport-Security "max-age=31536000; includeSubDomains"' >> "$VHOST_CONF"
grep -q 'X-Content-Type-Options' "$VHOST_CONF" || echo 'Header always set X-Content-Type-Options "nosniff"' >> "$VHOST_CONF"

# Redémarrage d'Apache
echo "🔄 Redémarrage d'Apache..."
systemctl reload apache2

# Ajout de la middleware Laravel
echo "🛡️ Ajout de la middleware Laravel SecureCookies..."

MIDDLEWARE_PATH="app/Http/Middleware/SecureCookies.php"
KERNEL_PATH="app/Http/Kernel.php"

cat > "$MIDDLEWARE_PATH" <<EOL
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Cookie;

class SecureCookies
{
    public function handle(Request \$request, Closure \$next)
    {
        \$response = \$next(\$request);

        foreach (\$response->headers->getCookies() as \$cookie) {
            if (\$cookie->getName() === 'XSRF-TOKEN') {
                \$response->headers->setCookie(
                    new Cookie(
                        'XSRF-TOKEN',
                        \$cookie->getValue(),
                        \$cookie->getExpiresTime(),
                        '/',
                        null,
                        true,
                        true,
                        false,
                        Cookie::SAMESITE_LAX
                    )
                );
            }
        }

        return \$response;
    }
}
EOL

# Ajout dans Kernel.php si absent
if ! grep -q "SecureCookies::class" "$KERNEL_PATH"; then
    sed -i "/protected \$middleware = \[/a \ \ \ \ \ \ App\\\Http\\\Middleware\\\SecureCookies::class," "$KERNEL_PATH"
    echo "✅ Middleware ajoutée au Kernel Laravel."
fi

echo "✅ Sécurisation terminée !"
