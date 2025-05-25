#!/bin/bash

# Clés reCAPTCHA v2 (à personnaliser)
SITE_KEY="6Leh5kgrAAAAAPirVaxyWN91pvFZoZZclvpGl4PR"
SECRET_KEY="6Leh5kgrAAAAAPyUPuEoVv5vRtmbqgMdv_rmYAGk"

# Couleurs
GREEN="\e[32m"
RED="\e[31m"
NC="\e[0m" # No Color

# Vérification de composer
if ! command -v composer &> /dev/null; then
    echo -e "${RED}Erreur : Composer n'est pas installé. Installez-le d'abord.${NC}"
    exit 1
fi

# Vérification si le package est déjà installé
if grep -q "anhskohbo/no-captcha" composer.json; then
    echo -e "${GREEN}Le package anhskohbo/no-captcha est déjà installé.${NC}"
else
    echo "Installation du package Laravel reCAPTCHA..."
    composer require anhskohbo/no-captcha
fi

# Ajout des clés dans .env si elles n'existent pas
if grep -q "NOCAPTCHA_SITEKEY" .env; then
    echo -e "${GREEN}Les clés NOCAPTCHA sont déjà présentes dans .env${NC}"
else
    echo -e "\nNOCAPTCHA_SITEKEY=$SITE_KEY" >> .env
    echo "NOCAPTCHA_SECRET=$SECRET_KEY" >> .env
    echo -e "${GREEN}Clés ajoutées dans .env${NC}"
fi

# Ajout de la config dans config/services.php
CONFIG_FILE="config/services.php"
if grep -q "'nocaptcha'" "$CONFIG_FILE"; then
    echo -e "${GREEN}La configuration 'nocaptcha' est déjà présente dans $CONFIG_FILE${NC}"
else
    echo "Ajout de la configuration dans config/services.php..."
    sed -i "/return \[/a\    'nocaptcha' => [\n        'sitekey' => env('NOCAPTCHA_SITEKEY'),\n        'secret' => env('NOCAPTCHA_SECRET'),\n    ]," "$CONFIG_FILE"
    echo -e "${GREEN}Configuration ajoutée à $CONFIG_FILE${NC}"
fi

# Conseils d'intégration
echo -e "${GREEN}Installation terminée avec succès.${NC}"
echo -e "\nAjoute dans ta vue Blade (formulaire) :"
echo -e "  {!! NoCaptcha::renderJs() !!}"
echo -e "  {!! NoCaptcha::display() !!}"

echo -e "\nEt dans ton contrôleur (validation) :"
echo -e "  'g-recaptcha-response' => 'required|captcha'"
