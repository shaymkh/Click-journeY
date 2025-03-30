<?php
session_start();

$erreur = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // Chargement des utilisateurs depuis le fichier JSON
    $utilisateurs = json_decode(file_get_contents("data/utilisateurs.json"), true);

    foreach ($utilisateurs as $utilisateur) {
        // Vérifie si le téléphone/mail correspond et le mot de passe est correct
        if (($utilisateur['login'] === $phone || $utilisateur['email'] === $phone)
            && password_verify($password, $utilisateur['motdepasse'])) {

            // Connexion OK → on sauvegarde les infos utiles en session
            $_SESSION['utilisateur'] = [
                'login' => $utilisateur['login'],
                'pseudo' => $utilisateur['pseudo'],
                'role' => $utilisateur['role']
            ];

            // Redirection selon le rôle
            if ($utilisateur['role'] === 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: accueil.php");
            }
            exit;
        }
    }

    // Sinon, erreur
    $erreur = "Identifiants incorrects.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="connexion.css">
</head>
<body>
	<div class="sidebar">
        <h2>Cy City Adventure</h2>
        <ul>
            <li><a href="accueil.php">🏠 Accueil</a></li>
            <li><a href="presentation.php">📜 Présentation</a></li>
            <li><a href="connexion.php">🔑 Se connecter</a></li>
            <li><a href="modifier_profil.php">✏️ Modifier votre profil</a></li>
            <li><a href="inscription.php">📝 Inscription</a></li>
            <li><a href="destinations.php">🌍 Destinations</a></li>
            <li><a href="admin.php">👨‍💻 Administrateur</a></li>
        </ul>
    </div>

    <div class="form-container">
        <h1>Se connecter</h1>

        <?php if (!empty($erreur)): ?>
            <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="phone">Numéro de téléphone ou e-mail</label>
                <input type="text" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone ou mail" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Votre mot de passe" required>
            </div>
            <div class="form-group">
                <button type="submit">Se connecter</button>
            </div>
        </form>
    </div>
</body>
</html>

