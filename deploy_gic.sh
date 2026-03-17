#!/bin/bash

# === CONFIGURATION ===
LOCAL_DIR="$HOME/gestion-courrier/"
REMOTE_USER="bobongo"
REMOTE_HOST="172.233.244.133"
REMOTE_DIR="/var/www/html/gic/gestion-courrier/"

# === SYNC AVEC RSYNC ===
echo "🔄 Déploiement de GIC vers Linode..."
rsync -avz --exclude=node_modules --exclude=vendor --exclude=".env" --delete "$LOCAL_DIR" "${REMOTE_USER}@${REMOTE_HOST}:${REMOTE_DIR}"

echo "✅ Déploiement terminé avec succès."
