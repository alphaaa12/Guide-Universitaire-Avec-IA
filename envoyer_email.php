<?php
include("navbar.php");
if ($_SESSION['utilisateur']['role'] !== 'admin') {
    echo "Accès refusé.";
    exit();
}

$conn = new mysqli("localhost", "root", "", "gestion_utilisateurs");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $to = $_POST['email'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    $headers = "From: admin@tonsite.com\r\n";

    if (mail($to, $subject, $message, $headers)) {
        echo "Email envoyé avec succès à $to";
    } else {
        echo "Erreur lors de l'envoi de l'email.";
    }
}
?>

<form method="POST" style="margin-top: 20px;">
    <input type="email" name="email" placeholder="Email destinataire" required><br><br>
    <input type="text" name="subject" placeholder="Objet" required><br><br>
    <textarea name="message" rows="6" placeholder="Message" required></textarea><br><br>
    <input type="submit" value="Envoyer l'email">
</form>
