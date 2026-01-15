<?php
session_start();
 use PHPMailer\PHPMailer\PHPMailer;
 use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$host = "localhost";
$user = "root";
$password = "";
$db = "gestion_utilisateurs";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Nettoyage des données
    $nom = trim($_POST["nom"]);
    $prenom = trim($_POST["prenom"]);
    $faculte = trim($_POST["faculte"]);
    $num_inscription = trim($_POST["num_inscription"]);
    $email = trim($_POST["email"]);
    $role = $_POST["role"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $confirme = $_POST["confirme"];

    // Vérification mot de passe
    if ($mot_de_passe !== $confirme) {
        echo "Les mots de passe ne correspondent pas.";
        exit();
    }

    // Vérification unicité numéro d'inscription
    $checkNum = $conn->prepare("SELECT id FROM utilisateurs WHERE num_inscription = ?");
    $checkNum->bind_param("s", $num_inscription);
    $checkNum->execute();
    if ($checkNum->get_result()->num_rows > 0) {
        echo "Ce numéro d'inscription est déjà utilisé.";
        exit();
    }

    // Vérification unicité email
    $checkEmail = $conn->prepare("SELECT id FROM utilisateurs WHERE email = ?");
    $checkEmail->bind_param("s", $email);
    $checkEmail->execute();
    if ($checkEmail->get_result()->num_rows > 0) {
        echo "Cet email est déjà utilisé.";
        exit();
    }

    // Hachage mot de passe
    $hash = hash('sha256', $mot_de_passe);

    // Gestion photo
    $photoName = "default.png";
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $allowed)) {
            $photoName = uniqid() . "." . $ext;
            move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photoName);
        } else {
            echo "Format de photo non autorisé.";
            exit();
        }
    }

    // Générer code de vérification
    $verification_code = bin2hex(random_bytes(16));

    // Insérer utilisateur
    $stmt = $conn->prepare("INSERT INTO utilisateurs (nom, prenom, faculte, num_inscription, email, role, mot_de_passe, photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $nom, $prenom, $faculte, $num_inscription, $email, $role, $hash, $photoName);

    if ($stmt->execute()) {
        // Préparation mail avec PHPMailer
        $mail = new PHPMailer(true);

        try {
            // Config SMTP Gmail
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'gharbiala12@gmail.com';    // Ton email Gmail
            $mail->Password = 'frvw iwoa muko hyqn';      // Ton mot de passe d'application
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Expéditeur & destinataire
            $mail->setFrom('gharbiala12@gmail.com', 'Ton Site');
            $mail->addAddress($email, $prenom);

            // Contenu
            $mail->isHTML(false);
            $mail->Subject = 'Vérification de votre compte';
            $verification_link = "http://localhost/verification.php?code=" . $verification_code;
            $mail->Body = "Bonjour $prenom,\n\nMerci de cliquer sur le lien suivant pour vérifier votre compte :\n$verification_link\n\nCordialement,\nL'équipe";

            $mail->send();
            header("Location: connexion.php");
            exit();
;
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email: {$mail->ErrorInfo}";
        }
    } else {
        echo "Erreur lors de l'inscription: " . $conn->error;
    } 
} 
?>

<link rel="stylesheet" href="inscription.css">

<h2 class="title">Formulaire d'inscription</h2>
<form class="form" method="POST" enctype="multipart/form-data" action="inscription.php">
    <label>Photo de profil :
        <input class="input" type="file" name="photo" accept=".jpg,.jpeg,.png,.gif">
    </label>
    <label>
        <input class="input" type="text" name="nom" placeholder=" " required>
        <span>Nom</span>
    </label>
    <label>
        <input class="input" type="text" name="prenom" placeholder=" " required>
        <span>Prénom</span>
    </label>
    <label>
        <input class="input" type="text" name="faculte" placeholder=" ">
        <span>Faculté</span>
    </label>
    <label>
        <input class="input" type="text" name="num_inscription" placeholder=" " required>
        <span>Numéro d'inscription</span>
    </label>
    <label>
        <input class="input" type="email" name="email" placeholder=" " required>
        <span>Email</span>
    </label>
    <label>
        <select class="input" name="role" required>
            <option value="" disabled selected>Vous êtes...</option>
            <option value="etudiant">Étudiant</option>
            <option value="enseignant">Enseignant</option>
            <option value="personne">Personne</option>
        </select>
        <span>Rôle</span>
    </label>
    <label>
        <input class="input" type="password" name="mot_de_passe" placeholder=" " required>
        <span>Mot de passe</span>
    </label>
    <label>
        <input class="input" type="password" name="confirme" placeholder=" " required>
        <span>Confirmer mot de passe</span>
    </label>
    <button class="submit" type="submit">S'inscrire</button>
</form>

<script>
    const hour = new Date().getHours();
    if (hour >= 18 || hour <= 6) {
        document.body.classList.add('dark');
    }
</script>