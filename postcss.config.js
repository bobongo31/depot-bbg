// postcss.config.js
import tailwindcss from 'tailwindcss';
import autoprefixer from 'autoprefixer';
import tailwindcssPostcss from '@tailwindcss/postcss'; // Importer le package séparé

/** @type {import('postcss').Config} */
export default {
  plugins: [
    tailwindcssPostcss(),  // Utilisation du plugin séparé
    autoprefixer
  ],
};
