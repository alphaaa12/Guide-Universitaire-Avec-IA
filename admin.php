<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "gestion_utilisateurs";

// Connexion
$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Échec de connexion : " . $conn->connect_error);
}

// Infos de l'admin à insérer
$nom = "Admin Général";
$email = "admin@gmail.com";
$mot_de_passe_clair = "123456"; // Le mot de passe à utiliser pour se connecter
$role = "admin";

// Hachage du mot de passe
$mot_de_passe_hash = password_hash($mot_de_passe_clair, PASSWORD_DEFAULT);

// Préparer et exécuter l'insertion
$stmt = $conn->prepare("INSERT INTO utilisateurs (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $nom, $email, $mot_de_passe_hash, $role);

if ($stmt->execute()) {
    echo "✅ Admin ajouté avec succès. Email: $email | Mot de passe: $mot_de_passe_clair";
} else {
    echo "❌ Erreur : " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
