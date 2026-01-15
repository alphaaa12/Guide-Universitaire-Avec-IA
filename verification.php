<?php
$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    $stmt = $conn->prepare("SELECT id, is_verified FROM utilisateurs WHERE verification_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if ($user['is_verified']) {
            echo "<p>Votre compte est déjà vérifié.</p>";
        } else {
            $update = $conn->prepare("UPDATE utilisateurs SET is_verified = 1, verification_code = NULL WHERE id = ?");
            $update->bind_param("i", $user['id']);
            $update->execute();
            echo "<p>Votre compte a bien été vérifié. Vous allez être redirigé vers la page de connexion.</p>";
            header("Refresh: 5; url=connexion.php");
            exit();
        }
    } else {
        echo "<p>Code de vérification invalide.</p>";
    }
} else {
    echo "<p>Aucun code fourni.</p>";
}
?>
