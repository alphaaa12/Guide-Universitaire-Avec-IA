<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<link rel="stylesheet" href="styleind.css">
<script defer src="scriptind.js"></script>
<head>
    <meta charset="UTF-8">
    <title>Accueil - Gestion Utilisateurs</title>
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="slider">
  <div class="slides" id="slides">
    <img src="uploads/image1.jpg" alt="Image 1">
    <img src="uploads/image2.jpg" alt="Image 2">
    <img src="uploads/image3.jpg" alt="Image 3">
  </div>
</div>


        <h1>Bienvenue sur la Gestion Utilisateurs</h1>

        <?php if (isset($_SESSION['utilisateur'])): ?>
            <p>Bonjour, <strong><?= htmlspecialchars($_SESSION['utilisateur']['nom']) ?></strong> !</p>

            <?php if ($_SESSION['utilisateur']['role'] === 'general'): ?>
                <a href="admin_panel.php">Panneau Admin</a>
                <a href="accueil_admin.php">Accueil Compte</a>
            <?php elseif ($_SESSION['utilisateur']['role'] === 'fac'): ?>
                    <a href="dashbboard_fac.php">Panneau Admin faculté</a>
            <?php elseif ($_SESSION['utilisateur']['role'] === 'foyer'): ?>
                <a href="dashbboard_foyer.php">Panneau Admin</a>
            <?php elseif ($_SESSION['utilisateur']['role'] === 'enseignant'): ?>
                <a href="accueil_enseignant.php">Accueil Compte</a>
            <?php elseif ($_SESSION['utilisateur']['role'] === 'etudiant'): ?>
                <a href="accueil_etudiant.php">Accueil Compte</a>
            <?php else: ?>
                <a href="accueil_personne.php">Accueil Compte</a>
            <?php endif; ?>

            <a href="profile.php">Mon Profil</a>
            <a href="logout.php" style="background:#dc3545;">Déconnexion</a>

        <?php else: ?>
            <a href="inscription.php">Inscription</a>
            <a href="connexion.php">Connexion</a>
        <?php endif; ?>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
 <br>
            
            
</body>
</html>
        }