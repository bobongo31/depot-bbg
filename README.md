# Suivi Google Analytics 4 dans Laravel

Ce script configure automatiquement le suivi Google Analytics :

- Ajoute le code GA4 dans `layouts.app`
- Injecte l’ID de mesure dans le fichier `.env`
- Insère `@sendAnalyticsClientId` (si tu l’utilises plus tard)

## Exemple de code injecté

```html
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-13LEHFNS9X"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-13LEHFNS9X');
</script>
```

## À savoir

Ton layout doit contenir `</head>` pour que l’injection fonctionne.

