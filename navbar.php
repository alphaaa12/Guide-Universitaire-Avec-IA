<?php
if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}
$user = $_SESSION['utilisateur'];
?>

<style>
nav {
    background: #007BFF;
    color: white;
    padding: 15px 20px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
nav a {
    color: white;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
}
nav a:hover {
    text-decoration: underline;
}
</style>

<nav>
    <div>
        Bonjour, <?= htmlspecialchars($user['nom']) ?> (<?= $user['role'] ?>)
    </div>
    <div>
    <a href="index.php">acceuil</a>
        <a href="profile.php">profile</a>
        <a href="logout.php">Déconnexion</a>


    </div>
</nav>
