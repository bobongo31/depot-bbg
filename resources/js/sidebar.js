// Attendre que le DOM soit complètement chargé avant d'exécuter le script
document.addEventListener('DOMContentLoaded', function () {
    // Sélectionner le bouton du menu et la sidebar
    const menuBtn = document.getElementById('menu-btn');
    const sidebar = document.getElementById('sidebar');
    
    // Ajouter l'événement pour afficher/cacher la sidebar
    menuBtn.addEventListener('click', function () {
        sidebar.classList.toggle('hidden');  // Toggle la visibilité de la sidebar
    });

    // Sélectionner le bouton du profil et le menu déroulant
    const profileBtn = document.getElementById('profile-btn');
    const profileMenu = document.getElementById('profile-menu');
    
    // Ajouter l'événement pour afficher/cacher le menu déroulant du profil
    profileBtn.addEventListener('click', function () {
        profileMenu.classList.toggle('hidden');
    });
});
