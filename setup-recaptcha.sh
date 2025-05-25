#!/bin/bash

# Clés à personnaliser
SITE_KEY="6Leh5kgrAAAAAPirVaxyWN91pvFZoZZclvpGl4PR"
SECRET_KEY="6Leh5kgrAAAAAPyUPuEoVv5vRtmbqgMdv_rmYAGk"

echo "Installation du package Laravel reCAPTCHA..."
composer require anhskohbo/no-captcha

echo "Ajout des clés dans .env..."
echo -e "\nNOCAPTCHA_SITEKEY=$SITE_KEY" >> .env
echo "NOCAPTCHA_SECRET=$SECRET_KEY" >> .env

echo "Ajout de la config dans config/services.php..."
CONFIG='
    '\''nocaptcha'\'' => [
        '\''sitekey'\'' => env('\''NOCAPTCHA_SITEKEY''),
        '\''secret'\'' => env('\''NOCAPTCHA_SECRET''),
    ],'
sed -i "/return \[/a$CONFIG" config/services.php

echo "Installation terminée. N'oublie pas d'ajouter dans ton Blade :"
echo "{!! NoCaptcha::renderJs() !!} et {!! NoCaptcha::display() !!}"
echo "Et dans le controller : 'g-recaptcha-response' => 'required|captcha'"
