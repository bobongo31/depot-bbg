#!/bin/bash

GREEN='\033[0;32m'
NC='\033[0m'

LAYOUT_FILE="resources/views/layouts/app.blade.php"
GA_ID="G-13LEHFNS9X"

echo -e "${GREEN}▶ Mise à jour de l'ID de mesure GA dans .env...${NC}"
if grep -q "^GA_MEASUREMENT_ID=" .env; then
  sed -i "s/^GA_MEASUREMENT_ID=.*/GA_MEASUREMENT_ID=$GA_ID/" .env
else
  echo "GA_MEASUREMENT_ID=$GA_ID" >> .env
fi

# Code GA sans sed (multiligne via echo)
GA_TAG=$(cat <<EOF
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=$GA_ID"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '$GA_ID');
</script>
EOF
)

echo -e "${GREEN}▶ Insertion du tag GA dans le layout...${NC}"
if [ -f "$LAYOUT_FILE" ]; then
  # Ajouter le code GA avant </head> s'il n'existe pas déjà
  if ! grep -q "googletagmanager.com/gtag/js" "$LAYOUT_FILE"; then
    awk -v tag="$GA_TAG" '/<\/head>/ { print tag; print; next }1' "$LAYOUT_FILE" > tmpfile && mv tmpfile "$LAYOUT_FILE"
    echo "→ Code GA inséré avant </head>"
  else
    echo "→ Code GA déjà présent"
  fi

  # Ajouter @sendAnalyticsClientId si nécessaire
  if ! grep -q "@sendAnalyticsClientId" "$LAYOUT_FILE"; then
    echo "@sendAnalyticsClientId" >> "$LAYOUT_FILE"
    echo "→ @sendAnalyticsClientId ajouté"
  fi
else
  echo "⚠ Layout introuvable à $LAYOUT_FILE"
fi

echo -e "${GREEN}▶ Création de README.md...${NC}"
cat <<EOF > README.md
# Suivi Google Analytics 4 dans Laravel

Ce script configure automatiquement le suivi Google Analytics :

- Ajoute le code GA4 dans \`layouts.app\`
- Injecte l’ID de mesure dans le fichier \`.env\`
- Insère \`@sendAnalyticsClientId\` (si tu l’utilises plus tard)

## Exemple de code injecté

\`\`\`html
$GA_TAG
\`\`\`

## À savoir

Ton layout doit contenir \`</head>\` pour que l’injection fonctionne.

EOF

echo -e "${GREEN}✅ Script terminé avec succès.${NC}"
