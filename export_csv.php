<?php
$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=utilisateurs.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Nom', 'Prénom', 'Email', 'Faculté', 'Numéro', 'Rôle']);

$result = $conn->query("SELECT * FROM utilisateurs");

while ($row = $result->fetch_assoc()) {
    fputcsv($output, [
        $row['id'],
        $row['nom'],
        $row['prenom'],
        $row['email'],
        $row['faculte'],
        $row['num_inscription'],
        $row['role']
    ]);
}

fclose($output);
exit;
