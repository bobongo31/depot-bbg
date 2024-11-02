// Timer d'inactivité
let inactivityTime = function () {
    let timer;

    const resetTimer = () => {
        clearTimeout(timer);
        timer = setTimeout(logout, 120000); // 5 minutes = 120000 ms
    };

    const logout = () => {
        // Rediriger vers la page de déconnexion
        window.location.href = 'logout.php'; // Remplacez par votre page de déconnexion
    };

    // Réinitialiser le minuteur sur les événements utilisateur
    window.onload = resetTimer;
    document.onmousemove = resetTimer;
    document.onkeydown = resetTimer;
};

inactivityTime();
