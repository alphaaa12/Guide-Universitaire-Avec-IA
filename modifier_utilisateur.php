<?php
include("navbar.php");

if ($_SESSION['utilisateur']['role'] !== 'admin') {
    echo "Accès refusé.";
    exit();
}

$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

$id = intval($_GET["id"]);
$user = $conn->query("SELECT * FROM utilisateurs WHERE id = $id")->fetch_assoc();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $faculte = $_POST["faculte"];
    $num = $_POST["num_inscription"];
    $role = $_POST["role"];

    $stmt = $conn->prepare("UPDATE utilisateurs SET nom=?, prenom=?, email=?, faculte=?, num_inscription=?, role=? WHERE id=?");
    $stmt->bind_param("ssssssi", $nom, $prenom, $email, $faculte, $num, $role, $id);
    $stmt->execute();

    header("Location: admin_panel.php");
    exit();
}
?>

<link rel="stylesheet" href="style.css">

<h2>Modifier Utilisateur</h2>

<form method="POST">
    <input type="text" name="nom" value="<?= htmlspecialchars($user['nom']) ?>" required>
    <input type="text" name="prenom" value="<?= htmlspecialchars($user['prenom']) ?>" required>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
    <input type="text" name="faculte" value="<?= htmlspecialchars($user['faculte']) ?>">
    <input type="text" name="num_inscription" value="<?= htmlspecialchars($user['num_inscription']) ?>" required>
    
    <select name="role" required>
        <option value="etudiant" <?= $user['role'] === 'etudiant' ? 'selected' : '' ?>>Étudiant</option>
        <option value="enseignant" <?= $user['role'] === 'enseignant' ? 'selected' : '' ?>>Enseignant</option>
        <option value="personne" <?= $user['role'] === 'personne' ? 'selected' : '' ?>>Personne</option>
        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
    </select>

    <input type="submit" value="Enregistrer les modifications">
</form>
