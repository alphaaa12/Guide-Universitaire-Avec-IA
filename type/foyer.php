<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    // Non connecté, retourne à login
    header("Location: connexion.php");
    exit();
}

$role = $_SESSION['utilisateur']['role'];

// اتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Utilisateur Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #eef2f3;
        }
        header {
            background-color: #0d6efd;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar {
            width: 220px;
            background-color: #1b1e21;
            color: white;
            height: 100vh;
            position: fixed;
            top: 60px;
            left: 0;
            padding-top: 20px;
        }
        .sidebar a {
            display: block;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: #343a40;
        }
        .main {
            margin-left: 220px;
            padding: 40px;
        }
        h1 {
            margin-bottom: 20px;
        }
    </style>
    <script>
    window.onload = function () {
        document.querySelector('.main').style.opacity = 0;
        setTimeout(() => {
            document.querySelector('.main').style.transition = "opacity 1s ease";
            document.querySelector('.main').style.opacity = 1;
        }, 200);
    }
</script>

</head>
<body>

<header>
    <h2>Dashboard Utilisateur</h2>
<div>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['nom']); ?> 👋 | <a href="logout.php" style="color: #ffc107;">Déconnexion</a></div>
</header>

<div class="sidebar">
    <a href="#">📋 Voir Services</a>
    <a href="#">👤 Mon Profil</a>
    <a href="#">📩 Contacter Admin</a>
</div>

<div class="main">
    <h1>Bienvenue sur votre espace</h1>
    <p>Vous pouvez accéder à vos informations, services disponibles et contacter l’administration.</p>
</div>

</body>
</html>

