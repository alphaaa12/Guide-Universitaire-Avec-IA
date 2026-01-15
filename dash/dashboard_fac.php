<?php
session_start();
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'fac') {
    header("Location: connexion.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            margin: 0;
            background: #f0f2f5;
        }
        header {
            background-color: #343a40;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .sidebar {
            width: 220px;
            background-color: #212529;
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
            background-color: #495057;
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
    <h2>Dashboard Admin</h2>
    <div>Bienvenue, <?= $_SESSION['nom']; ?> 👨‍💼 | <a href="logout.php" style="color: #ffc107;">Déconnexion</a></div>
</header>

<div class="sidebar">
    <a href="#">👥 Gérer Utilisateurs</a>
    <a href="#">🛠️ Gérer Services</a>
    <a href="#">📊 Statistiques</a>
</div>

<div class="main">
    <h1>Panneau d’administration</h1>
    <p>Voici les actions disponibles pour vous en tant qu’administrateur.</p>
</div>

</body>
</html>
