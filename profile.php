<?php
session_start();

if (!isset($_SESSION['utilisateur'])) {
    header("Location: connexion.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

$userId = $_SESSION['utilisateur']['id'];

$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ancien_pass = $_POST['ancien_password'];
    $nouveau_pass = $_POST['nouveau_password'];
    $confirme_pass = $_POST['confirme_password'];

    // Récupérer le hash actuel
    $stmt = $conn->prepare("SELECT mot_de_passe FROM utilisateurs WHERE id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($ancien_pass, $user['mot_de_passe'])) {
        if ($nouveau_pass === $confirme_pass) {
            $nouveau_hash = password_hash($nouveau_pass, PASSWORD_DEFAULT);
            $update = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE id = ?");
            $update->bind_param("si", $nouveau_hash, $userId);
            $update->execute();
            $message = "Mot de passe mis à jour avec succès.";
        } else {
            $message = "Le nouveau mot de passe et la confirmation ne correspondent pas.";
        }
    } else {
        $message = "Ancien mot de passe incorrect.";
    }
}

// Récupérer les infos utilisateur
$stmt = $conn->prepare("SELECT nom, prenom, email, faculte, num_inscription, role, photo FROM utilisateurs WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$utilisateur = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <title>Profil - <?= htmlspecialchars($utilisateur['nom']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background: #f9f9f9; padding: 30px; }
        .container { max-width: 600px; margin: auto; background: #fff; padding: 20px; border-radius: 8px; }
        img { border-radius: 50%; width: 100px; height: 100px; object-fit: cover; }
        label { display: block; margin-top: 15px; font-weight: bold; }
        input[type="password"], textarea { width: 100%; padding: 8px; margin-top: 5px; }
        .message { margin-top: 15px; color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Mon Profil</h2>
        <img src="uploads/<?= htmlspecialchars($utilisateur['photo']) ?>" alt="Photo de profil" />
        <p><strong>Nom :</strong> <?= htmlspecialchars($utilisateur['nom']) ?></p>
        <p><strong>Prénom :</strong> <?= htmlspecialchars($utilisateur['prenom']) ?></p>
        <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></p>
        <p><strong>Faculté :</strong> <?= htmlspecialchars($utilisateur['faculte']) ?></p>
        <p><strong>Numéro d'inscription :</strong> <?= htmlspecialchars($utilisateur['num_inscription']) ?></p>
        <p><strong>Rôle :</strong> <?= htmlspecialchars($utilisateur['role']) ?></p>

        <h3>Modifier le mot de passe</h3>
        <?php if ($message): ?>
            <p class="<?= strpos($message, 'succès') !== false ? 'message' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        <?php endif; ?>

        <form method="POST" action="profile.php">
            <label for="ancien_password">Ancien mot de passe :</label>
            <input type="password" name="ancien_password" id="ancien_password" required />

            <label for="nouveau_password">Nouveau mot de passe :</label>
            <input type="password" name="nouveau_password" id="nouveau_password" required />

            <label for="confirme_password">Confirmer nouveau mot de passe :</label>
            <input type="password" name="confirme_password" id="confirme_password" required />

            <input type="submit" value="Mettre à jour" style="margin-top: 15px; padding: 10px 15px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer;">
        </form>

        <p><a href="index.php" style="display: inline-block; margin-top: 20px; color: #007BFF;">Retour à l'accueil</a></p>
    </div>
</body>
</html>
