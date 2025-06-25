#!/bin/bash

# Variables
ENV_FILE=".env"
SERVICE_FILE="config/services.php"
APP_LAYOUT="resources/views/layouts/app.blade.php"
REGISTER_VIEW="/var/www/html/gic/gestion-courrier/resources/views/inscription.blade.php"
SITE_KEY="6LfHYEkrAAAAAHQVpVZ4EvqiCq4_FwWBSkTMtSdo"
SECRET_KEY="6LfHYEkrAAAAAAiYyvGkarMAYWmnzKQsllp1ODd_"

echo "🚀 Début de la configuration reCAPTCHA v3..."

# 1. Ajout des clés dans le fichier .env
if ! grep -q "RECAPTCHA_SITE_KEY_V3" "$ENV_FILE"; then
    echo "Ajout des clés reCAPTCHA dans $ENV_FILE..."
    {
        echo ""
        echo "# Clés reCAPTCHA v3"
        echo "RECAPTCHA_SITE_KEY_V3=$SITE_KEY"
        echo "RECAPTCHA_SECRET_KEY_V3=$SECRET_KEY"
    } >> "$ENV_FILE"
else
    echo "✔️ Les clés reCAPTCHA sont déjà présentes dans $ENV_FILE"
fi

# 2. Ajout dans config/services.php
if ! grep -q "'recaptcha_v3'" "$SERVICE_FILE"; then
    echo "Ajout de la configuration reCAPTCHA dans $SERVICE_FILE..."
    sed -i "/return \[/a \ \ \ \ 'recaptcha_v3' => [\n\ \ \ \ \ \ \ \ 'site' => env('RECAPTCHA_SITE_KEY_V3'),\n\ \ \ \ \ \ \ \ 'secret' => env('RECAPTCHA_SECRET_KEY_V3'),\n\ \ \ \ ]," "$SERVICE_FILE"
else
    echo "✔️ Configuration reCAPTCHA déjà présente dans $SERVICE_FILE"
fi

# 3. Injection du script JS reCAPTCHA dans app.blade.php
if ! grep -q "recaptcha/api.js?render=" "$APP_LAYOUT"; then
    echo "Injection du script reCAPTCHA JS dans $APP_LAYOUT..."
    sed -i "/<\/head>/i <script src=\"https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha_v3.site') }}\"></script>" "$APP_LAYOUT"
else
    echo "✔️ Script reCAPTCHA déjà injecté dans $APP_LAYOUT"
fi

# 4. Ajout du script d'envoi du token reCAPTCHA dans inscription.blade.php
if ! grep -q "grecaptcha.ready(function()" "$REGISTER_VIEW"; then
    echo "Ajout du script de génération de token dans $REGISTER_VIEW..."
    cat <<'EOL' >> "$REGISTER_VIEW"

<script>
grecaptcha.ready(function() {
    grecaptcha.execute('{{ config('services.recaptcha_v3.site') }}', {action: 'register'}).then(function(token) {
        let input = document.createElement('input');
        input.setAttribute('type', 'hidden');
        input.setAttribute('name', 'recaptcha_token');
        input.setAttribute('value', token);
        document.forms[0].appendChild(input);
    });
});
</script>
EOL
else
    echo "✔️ Script token reCAPTCHA déjà présent dans $REGISTER_VIEW"
fi

echo "✅ Script terminé avec succès."
echo "⚠️ Pense à vérifier les clés dans $ENV_FILE et à lancer 'php artisan config:clear' si nécessaire."
