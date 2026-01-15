<?php
session_start();

if (!isset($_SESSION['utilisateur']) && !isset($_SESSION['admin'])) {
    header("Location: connexion.php");
    exit;
}

include("navbar.php");

// Si admin connecté
if (isset($_SESSION['admin'])) {
    echo "<h2>Bienvenue Admin</h2>";
    echo "<p>Gestion du site et des utilisateurs.</p>";
    echo '<div style="margin-top:30px;">
            <a href="admin/users.php" class="espace-btn">Gestion Utilisateurs</a>
            <a href="admin/settings.php" class="espace-btn">Paramètres</a>
          </div>';
}
// Si utilisateur connecté
elseif (isset($_SESSION['utilisateur'])) {
    $role = $_SESSION['utilisateur']['role'];

    switch ($role) {
        case "etudiant":
            echo "<h2>Bienvenue Étudiant</h2>";
            echo "<p>Accédez à vos cours et documents.</p>";
            echo '<div style="margin-top:30px;">
                    <a href="type/faculte.php" class="espace-btn">Faculté</a>
                    <a href="type/resto.php" class="espace-btn">Resto</a>
                    <a href="type/foyer.php" class="espace-btn">Foyer</a>
                    <a href="type/car.php" class="espace-btn">Car</a>
                  </div>';
            break;

        case "enseignant":
            echo "<h2>Bienvenue Enseignant</h2>";
            echo "<p>Accédez à vos cours et vos ressources pédagogiques.</p>";
            echo '<div style="margin-top:30px;">
                    <a href="enseignant/cours.php" class="espace-btn">Mes Cours</a>
                    <a href="enseignant/emploi.php" class="espace-btn">Emploi du temps</a>
                  </div>';
            break;

        case "personne":
            echo "<h2>Bienvenue</h2>";
            echo "<p>Accédez à vos services personnalisés.</p>";
            echo '<div style="margin-top:30px;">
                    <a href="personne/services.php" class="espace-btn">Services</a>
                  </div>';
            break;

        default:
            echo "<h2>Bienvenue Utilisateur</h2>";
            echo "<p>Bienvenue sur le site.</p>";
            break;
    }
}
else {
    echo "<p>Accès non autorisé.</p>";
}
?>
