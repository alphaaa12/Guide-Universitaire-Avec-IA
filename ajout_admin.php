<?php
session_start();

if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'general') {
    header('Location: connexion.php');
    exit();
}

$conn = new mysqli('localhost', 'root', '', 'gestion_utilisateurs');
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $valid_roles = ['faculte', 'resto', 'foyer', 'car'];
    if (!in_array($role, $valid_roles)) {
        $msg = "Rôle invalide";
    } else {
        // Vérifier si email existe déjà
        $check = $conn->prepare("SELECT id FROM admins WHERE email = ?");
        $check->bind_param("s", $email);
        $check->execute();
        $res_check = $check->get_result();
        if ($res_check->num_rows > 0) {
            $msg = "Email déjà utilisé";
        } else {
            $pass_hache = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO admins (nom, email, mot_de_passe, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $nom, $email, $pass_hache, $role);
            if ($stmt->execute()) {
                $msg = "Admin ajouté avec succès";
            } else {
                $msg = "Erreur lors de l'ajout : " . $conn->error;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ajouter un Admin</title>
</head>
<body>

<h2>Ajouter un Admin</h2>
<?php if ($msg) echo "<p>$msg</p>"; ?>

<form method="POST" action="">
    Nom: <input type="text" name="nom" required><br>
    Email: <input type="email" name="email" required><br>
    Mot de passe: <input type="password" name="password" required><br>
    Rôle:
    <select name="role" required>
        <option value="">--Sélectionner rôle--</option>
        <option value="faculte">Faculté</option>
        <option value="resto">Resto</option>
        <option value="foyer">Foyer</option>
        <option value="car">Car</option>
    </select><br>
    <button type="submit">Ajouter</button>
    <a href="admin_panel.php">retourne</a>
</form>

</body>
</html>
