<?php
session_start();

$erreur = "";

// Vérification de la méthode POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';

    // On met ici le chemin correct vers le fichier utilisateurs.json
    $filePath = 'utilisateurs.json';  // Chemin vers utilisateurs.json dans le dossier htdocs
    
    // On vérifie si le fichier existe
    if (file_exists($filePath)) {
        $utilisateurs = json_decode(file_get_contents($filePath), true);

        if ($utilisateurs === null) {
            $erreur = "Erreur de lecture du fichier utilisateurs.json.";
        } else {
            // On parcourt les utilisateurs
            foreach ($utilisateurs as $utilisateur) {
                // Vérifie si le téléphone/mail correspond et le mot de passe est correct
                if (($utilisateur['phone'] === $phone || $utilisateur['email'] === $phone) &&
                    password_verify($password, $utilisateur['motdepasse'])) {

                    // Connexion OK → on sauvegarde les infos utiles en session
                    $_SESSION['utilisateur'] = [
                        'phone' => $utilisateur['phone'],
                        'firstName' => $utilisateur['firstName'],
                        'lastName' => $utilisateur['lastName'],
                        'role' => $utilisateur['statut']
                    ];

                    // Redirection selon le rôle
                    if ($utilisateur['statut'] === 'admin') {
                        header("Location: admin.php");
                    } else {
                        header("Location: accueil.php");
                    }
                    exit;
                }
            }

            // Si aucun utilisateur n'a été trouvé
            $erreur = "Identifiants ou mot de passe incorrect.";
        }
    } else {
        $erreur = "Le fichier utilisateurs.json n'a pas été trouvé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="connexion.css">
    <script src="connexion.js" defer></script>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="theme.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body>
  <!-- 🔹 Barre de Navigation -->
  <nav class="navbar">
    <ul>
      <li><a href="accueil.php">Accueil</a></li>
      <li><a href="presentation.php">Présentation</a></li>
      <li><a href="connexion.php">Se Connecter</a></li>
      <li><a href="inscription.php">Inscription</a></li>
      <li><a href="modifier_profil.php">Modifier le Profil</a></li>
      <li><a href="destinations.php">Destinations</a></li>
      <li><a href="admin.php">Administrateur</a></li>
      <!-- 🔘 Bouton thème -->
      <li>
        <button
          type="button"
          data-theme-toggle
          aria-label="Changer de thème"
          class="theme-toggle-btn"
        >
          Thème Sombre
        </button>
<li>
  <button
    type="button"
    id="btn-accessibilite"
    class="theme-toggle-btn"
  >
    Malvoyant
  </button>
</li>

      </li>
    </ul>
  </nav>

  <!-- Contenu principal -->
  <div class="form-container">
    <h1>Se connecter</h1>

    <?php if (!empty($erreur)): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <form method="POST" id="formConnexion">
        <div class="form-group">
            <label for="phone">Numéro de téléphone ou e-mail</label>
            <input type="text" id="phone" name="phone" placeholder="Entrez votre numéro de téléphone ou mail" required>
            <small class="message-erreur"></small>
        </div>
        <div class="form-group">
            <label for="motdepasse">Mot de passe</label>
            <div class="motdepasse-container">
                <input type="password" id="motdepasse" name="motdepasse" placeholder="Votre mot de passe" required> <!-- Correction du type -->
                <span id="togglemotdepasse" class="fas fa-eye"></span>
            </div>
            <small class="message-erreur"></small>
        </div>
        <div class="form-group">
            <button type="submit">Se connecter</button>
        </div>
    </form>
  </div>

  <script src="accueil.js" defer></script> 
</body>
</html>
