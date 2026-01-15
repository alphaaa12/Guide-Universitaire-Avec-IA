<?php 
session_start();
include("navbar.php");

// Vérification des permissions
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'general') {
    echo "Accès refusé.";
    exit();
}

$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $email = $_POST["email"];
    $faculte = $_POST["faculte"];
    $num = $_POST["num_inscription"];
    $role = $_POST["role"];
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

    // Insertion de l'utilisateur
    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, email, faculte, num_inscription, role, mot_de_passe) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nom, $prenom, $email, $faculte, $num, $role, $password);
    $stmt->execute();
    $user_id = $conn->insert_id;

    // Attribution des droits admin uniquement si rôle = admin
    if ($role === 'admin') {
        $espaces = ['faculte', 'resto', 'foyer', 'car'];
        foreach ($espaces as $espace) {
            if (isset($_POST['admin_'.$espace])) {
                $res = $conn->query("SELECT id FROM espaces WHERE nom = '$espace'");
                if ($row = $res->fetch_assoc()) {
                    $espace_id = $row['id'];
                    $conn->query("INSERT INTO admin_espaces (utilisateur_id, espace_id) VALUES ($user_id, $espace_id)");
                }
            }
        }
    }

    header("Location: admin_panel.php");
    exit();
}
?>

<link rel="stylesheet" href="style.css">

<h2>Ajouter un nouvel utilisateur</h2>

<form method="POST">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="prenom" placeholder="Prénom" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="faculte" placeholder="Faculté">
    <input type="text" name="num_inscription" placeholder="Numéro d'inscription" required>

    <select name="role" required>
        <option value="etudiant">Étudiant</option>
        <option value="enseignant">Enseignant</option>
        <option value="personne">Personne</option>
        <option value="admin">Admin</option>
    </select>

    <input type="password" name="password" placeholder="Mot de passe" required>

    <div style="margin:10px 0;">
        <label><input type="checkbox" name="admin_faculte"> Admin Faculté</label>
        <label><input type="checkbox" name="admin_resto"> Admin Restaurant</label>
        <label><input type="checkbox" name="admin_foyer"> Admin Foyer</label>
        <label><input type="checkbox" name="admin_car"> Admin Station Car</label>
    </div>

    <input type="submit" value="Ajouter l'utilisateur">
</form>
