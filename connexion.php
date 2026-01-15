<?php
session_start();

$host = "localhost";
$user = "root";
$password = "";
$db = "gestion_utilisateurs";

$conn = new mysqli($host, $user, $password, $db);
if ($conn->connect_error) {
    die("Erreur de connexion : {$conn->connect_error}");
}

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"]);
    $pass = trim($_POST["mot_de_passe"]);

    // Chercher dans admins d'abord
    $stmt = $conn->prepare("SELECT * FROM admins WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $admin = $result->fetch_assoc();

    if ($admin && password_verify($pass, $admin['mot_de_passe'])) {
        $_SESSION['utilisateur'] = [
            'id' => $admin['id'],
            'nom' => $admin['nom'],
            'role' => $admin['role'],
            'type' => 'admin'
        ];
        rediriger_par_role($admin['role'], 'admin');
    } else {
        // Chercher dans utilisateurs
        $stmt = $conn->prepare("SELECT * FROM utilisateurs WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user) {
            $hash_db = $user['mot_de_passe'];

            // Si ancien hash SHA256
            if (strlen($hash_db) === 64 && ctype_xdigit($hash_db)) {
                if (hash('sha256', $pass) === $hash_db) {
                    // Mise à jour
                    $new_hash = password_hash($pass, PASSWORD_DEFAULT);
                    $stmt_update = $conn->prepare("UPDATE utilisateurs SET mot_de_passe = ? WHERE email = ?");
                    $stmt_update->bind_param("ss", $new_hash, $email);
                    $stmt_update->execute();

                    $_SESSION['utilisateur'] = $user + ['type' => 'utilisateur'];
                    rediriger_par_role($user["role"], 'utilisateur');
                } else {
                    $erreur = "Email ou mot de passe incorrect.";
                }
            } else {
                if (password_verify($pass, $hash_db)) {
                    $_SESSION['utilisateur'] = $user + ['type' => 'utilisateur'];
                    rediriger_par_role($user["role"], 'utilisateur');
                } else {
                    $erreur = "Email ou mot de passe incorrect.";
                }
            }
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    }
}

function rediriger_par_role($role, $type) {
    if ($type === 'admin') {
        switch ($role) {
            case "general":
                header("Location: admin_panel.php"); break;
            case "fac":
                header("Location: dash/dashboard_fac.php"); break;
            case "foyer":
                header("Location: dash/dashboard_foyer.php"); break;
            case "resto":
                    header("Location: dash/dashboard_resto.php"); break;
                case "car":
                    header("Location: dash/dashboard_car.php"); break;
            default:
                echo "Rôle admin non reconnu."; exit;
        }
    } else {
        switch ($role) {
            case "etudiant":
                header("Location: accueil_etudiant.php"); break;
            case "enseignant":
                header("Location: accueil_enseignant.php"); break;
            case "personne":
                header("Location: accueil_personne.php"); break;
            default:
                echo "Rôle utilisateur non reconnu."; exit;
        }
    }
    exit;
}
?>


<link rel="stylesheet" href="connexion.css">
<h2>Veuillez remplir les cases pour continuer...</h2>

<?php
if (!empty($erreur)) {
    echo "<p style='color:red;'>$erreur</p>";
}
?>

<form class="form" method="post" action="">
    <h2 id="heading">Connexion</h2>
    <div class="field">
        <input class="input-field" type="text" name="email" placeholder="Email" required>
    </div>
    <div class="field">
        <input class="input-field" type="password" name="mot_de_passe" placeholder="Password" required>
    </div>
    <div class="btn">
        <button class="button1" type="submit">Login</button>
        <button class="button2" type="reset">Reset</button>
    </div>
    <button class="button3" type="button">Forgot Password</button>
</form>



<script>
    const hour = new Date().getHours();
    if (hour >= 18 || hour <= 6) {
        document.body.classList.add('dark');
    }
</script>
