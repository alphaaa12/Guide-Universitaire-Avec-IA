<?php
session_start();

// تحقق من الدور
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'resto') {
    header("Location: connexion.php");
    exit;
}

// الاتصال بقاعدة البيانات
$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// إذا الفورم تم إرساله
if (isset($_POST['nom_plat'], $_POST['description']) && !empty($_FILES['image']['name'])) {

    // إنشاء مجلد images إذا مش موجود
    if (!is_dir("images")) {
        mkdir("images", 0777, true);
    }

    $target = "images/" . basename($_FILES['image']['name']);

    if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
        $check = $conn->query("SELECT id FROM plat_du_jour LIMIT 1");
        if ($check->num_rows === 0) {
            $stmt = $conn->prepare("INSERT INTO plat_du_jour (nom_plat, description, image_url) VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $_POST['nom_plat'], $_POST['description'], $target);
        } else {
            $row = $check->fetch_assoc();
            $stmt = $conn->prepare("UPDATE plat_du_jour SET nom_plat=?, description=?, image_url=? WHERE id=?");
            $stmt->bind_param("sssi", $_POST['nom_plat'], $_POST['description'], $target, $row['id']);
        }
        $stmt->execute();
        echo "<p style='color:green;'>Plat du jour enregistré avec succès !</p>";
    } else {
        echo "<p style='color:red;'>Erreur lors du téléchargement de l'image.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; margin: 0; background: #f0f2f5; }
        header { background-color: #343a40; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .sidebar { width: 220px; background-color: #212529; color: white; height: 100vh; position: fixed; top: 60px; left: 0; padding-top: 20px; }
        .sidebar a { display: block; padding: 12px 20px; color: white; text-decoration: none; }
        .sidebar a:hover { background-color: #495057; }
        .main { margin-left: 220px; padding: 40px; }
        h1 { margin-bottom: 20px; }
    </style>
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

    <h2>Définir le plat du jour</h2>
    <form method="post" enctype="multipart/form-data">
        <input type="text" name="nom_plat" placeholder="Nom du plat" required><br><br>
        <textarea name="description" placeholder="Description" required></textarea><br><br>
        <input type="file" name="image" accept="image/*" required><br><br>
        <button type="submit">Enregistrer</button>
    </form>
</div>

</body>
</html>
