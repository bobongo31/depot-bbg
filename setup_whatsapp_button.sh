#!/bin/bash

LAYOUT="resources/views/layouts/app.blade.php"
PHONE="+243897604018"
WHATSAPP_LINK="https://wa.me/${PHONE//+}"

echo "▶ Ajout du bouton WhatsApp animé dans $LAYOUT..."

# Injection du code juste avant </body>
sed -i '/<\/body>/i \
<!-- Bouton WhatsApp animé -->\
<style>\
#whatsapp-btn {\
  position: fixed;\
  bottom: 30px;\
  right: 30px;\
  width: 60px;\
  height: 60px;\
  background-color: #25D366;\
  border-radius: 50%;\
  box-shadow: 0 4px 8px rgba(0,0,0,0.3);\
  cursor: pointer;\
  animation: pulse 2s infinite;\
  display: flex;\
  justify-content: center;\
  align-items: center;\
  z-index: 1000;\
}\
#whatsapp-btn:hover {\
  animation-play-state: paused;\
}\
@keyframes pulse {\
  0%, 100% { transform: scale(1); }\
  50% { transform: scale(1.2); }\
}\
#whatsapp-btn svg {\
  width: 32px;\
  height: 32px;\
  fill: white;\
}\
</style>\
<a id=\"whatsapp-btn\" href=\"$WHATSAPP_LINK\" target=\"_blank\" rel=\"noopener noreferrer\" aria-label=\"Contact WhatsApp\">\
  <svg viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\
    <path d=\"M20.52 3.48A11.91 11.91 0 0012 0C5.37 0 0 5.37 0 12a11.94 11.94 0 001.85 6L0 24l6-1.85A11.94 11.94 0 0012 24c6.63 0 12-5.37 12-12 0-3.2-1.24-6.2-3.48-8.52zM12 21.5c-1.92 0-3.75-.54-5.32-1.56l-.38-.23-3.56 1.08 1.08-3.48-.24-.38A9.44 9.44 0 012.5 12c0-5.25 4.25-9.5 9.5-9.5 2.54 0 4.93.99 6.72 2.79A9.44 9.44 0 0121.5 12c0 5.25-4.25 9.5-9.5 9.5zm5.32-7.72l-2.38-1.08a.71.71 0 00-.84.21l-1.22 1.49a9.04 9.04 0 01-4.15-4.15l1.48-1.22a.71.71 0 00.2-.84L9.22 6.18a.71.71 0 00-.92-.31l-2.5 1a3.51 3.51 0 00-1.52 4.1 9.27 9.27 0 007.97 7.98 3.49 3.49 0 004.1-1.53l1-2.5a.71.71 0 00-.32-.93z\"/>\
  </svg>\
</a>' "$LAYOUT"

echo "✅ Bouton WhatsApp ajouté avec animation pulse."
