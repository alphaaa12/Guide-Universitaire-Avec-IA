<?php
session_start();
include 'navbar.php';
echo "Session role: " . ($_SESSION['utilisateur']['role'] ?? 'non défini') . "<br>";
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'general') {
    echo "Redirection...";
    header("Location: connexion.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

// Recherche
$search = isset($_GET['search']) ? $conn->real_escape_string($_GET['search']) : "";
$where = $search ? "WHERE nom LIKE '%$search%' OR email LIKE '%$search%'" : "";

// Tri
$sortable = ['id', 'nom', 'prenom', 'email', 'role', 'faculte'];
$sort = in_array($_GET['sort'] ?? '', $sortable) ? $_GET['sort'] : 'id';
$order = ($_GET['order'] ?? 'asc') === 'desc' ? 'desc' : 'asc';
$nextOrder = $order === 'asc' ? 'desc' : 'asc';

// Pagination
$limit = 10;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Suppression
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    if ($id !== $_SESSION['utilisateur']['id']) {
        $conn->query("DELETE FROM utilisateurs WHERE id = $id");
        header("Location: admin_panel.php?page=$page&search=$search&sort=$sort&order=$order");
        exit();
    }
}

// Total
$total = $conn->query("SELECT COUNT(*) AS total FROM utilisateurs $where")->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// Récupération
$query = "SELECT * FROM utilisateurs $where ORDER BY $sort $order LIMIT $limit OFFSET $offset";
$result = $conn->query($query);
?>

<link rel="stylesheet" href="style.css">

<h2>Panel d'administration</h2>

<form method="GET">
    <input type="text" name="search" placeholder="Rechercher nom ou email" value="<?= htmlspecialchars($search) ?>">
    <input type="submit" value="Rechercher">
</form> <br>

<div style="margin-top: 20px;">
    <a href="ajout_admin.php" style="padding: 8px 16px; background: green; color: white; text-decoration: none; border-radius: 5px;">➕ Ajouter Admin</a>
    <a href="ajouter_utilisateur.php" style="padding: 8px 16px; background: green; color: white; text-decoration: none; border-radius: 5px;">➕ Ajouter Utilisateur</a>
    <a href="export_csv.php" style="padding: 8px 16px; background: darkblue; color: white; text-decoration: none; border-radius: 5px; margin-left: 10px;">📤 Exporter CSV</a>
</div>

<br>

<table border="1" cellpadding="10" cellspacing="0" style="width: 100%; background: white; margin-top: 20px; border-collapse: collapse;">
    <tr style="background: #007BFF; color: white;">
        <th>Photo</th>
        <th><a href="?<?= http_build_query(['search' => $search, 'sort' => 'id', 'order' => $nextOrder, 'page' => $page]) ?>">ID</a></th>
        <th><a href="?<?= http_build_query(['search' => $search, 'sort' => 'nom', 'order' => $nextOrder, 'page' => $page]) ?>">Nom</a></th>
        <th><a href="?<?= http_build_query(['search' => $search, 'sort' => 'prenom', 'order' => $nextOrder, 'page' => $page]) ?>">Prénom</a></th>
        <th><a href="?<?= http_build_query(['search' => $search, 'sort' => 'email', 'order' => $nextOrder, 'page' => $page]) ?>">Email</a></th>
        <th><a href="?<?= http_build_query(['search' => $search, 'sort' => 'faculte', 'order' => $nextOrder, 'page' => $page]) ?>">Faculté</a></th>
        <th>Numéro</th>
        <th>Role</th>
        <th>Actions</th>
    </tr>

    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td>
                <img src="uploads/<?= htmlspecialchars($row['photo'] ?? 'default.png') ?>" width="50" height="50" style="border-radius:50%;">
            </td>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['nom']) ?></td>
            <td><?= htmlspecialchars($row['prenom']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['faculte']) ?></td>
            <td><?= htmlspecialchars($row['num_inscription']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <a href="modifier_utilisateur.php?id=<?= $row['id'] ?>">Modifier</a> |
                <a href="admin_panel.php?delete=<?= $row['id'] ?>&search=<?= urlencode($search) ?>&page=<?= $page ?>&sort=<?= $sort ?>&order=<?= $order ?>" onclick="return confirm('Supprimer cet utilisateur ?')">Supprimer</a>
            </td>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<br>

<div style="margin-top: 20px; text-align: center;">
    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?<?= http_build_query(['search' => $search, 'sort' => $sort, 'order' => $order, 'page' => $i]) ?>"
           style="margin: 0 5px; <?= $i == $page ? 'font-weight: bold; color: red;' : '' ?>">
            <?= $i ?>
        </a>
    <?php endfor; ?>
</div>
