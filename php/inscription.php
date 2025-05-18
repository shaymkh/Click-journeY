<?php
session_start();

// Chemin absolu vers le fichier utilisateurs.json
$cheminUsers = __DIR__ . '/../info/utilisateurs.json';

// Crée le fichier vide si nécessaire
if (!file_exists($cheminUsers)) {
    file_put_contents($cheminUsers, json_encode([]));
}

// Charge la liste des utilisateurs
// Lecture et décodage du JSON
$json = file_get_contents($cheminUsers);
$utilisateurs = json_decode($json, true);
// Vérification des erreurs de décodage JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erreur de parsing JSON : ' . json_last_error_msg());
}
if (!is_array($utilisateurs)) {
    $utilisateurs = [];
}

// Initialisation des variables utilisées dans le formulaire
$erreurs = [];
$pseudo  = '';
$email   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupère et nettoie les données du formulaire
    $pseudo = trim($_POST['username'] ?? '');
    $email  = trim($_POST['email'] ?? '');
    $mdp    = $_POST['password'] ?? '';
    $mdp2   = $_POST['confirm-password'] ?? '';

    // Validation des champs
    if ($pseudo === '') {
        $erreurs[] = 'Le nom d\'utilisateur est requis.';
    }
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'L\'adresse e-mail est invalide.';
    }
    if (strlen($mdp) < 2) {
        $erreurs[] = 'Le mot de passe doit contenir au moins 2 caractères.';
    }
    if ($mdp !== $mdp2) {
        $erreurs[] = 'Les mots de passe ne correspondent pas.';
    }

    // Vérification de l'unicité
    foreach ($utilisateurs as $u) {
        if (strcasecmp($u['login'], $pseudo) === 0) {
            $erreurs[] = 'Ce nom d\'utilisateur est déjà pris.';
            break;
        }
        if (strcasecmp($u['email'], $email) === 0) {
            $erreurs[] = 'Cette adresse e-mail est déjà utilisée.';
            break;
        }
    }

    // Si aucune erreur, on ajoute l'utilisateur
    if (empty($erreurs)) {
        $utilisateurs[] = [
            'login'             => $pseudo,
            'mot_de_passe'      => password_hash($mdp, PASSWORD_DEFAULT),
            'role'              => 'user',
            'email'             => $email,
            'date_inscription'  => date('Y-m-d'),
            'derniere_connexion'=> null,
            'voyages_achetes'   => []
        ];
        // Sauvegarde back dans le fichier JSON
        file_put_contents(
            $cheminUsers,
            json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        // Connexion automatique
        $_SESSION['login'] = $pseudo;
        header('Location: connexion.php');
        exit;
    }
}
?>

<!-- inscription.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Inscription – CY City Adventure</title>
  <link rel="stylesheet" href="inscription.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="presentationn.html">Présentation</a></li>
      <li><a href="voyage.php">Nos destinations</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="admin.php">Administrateur</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <!-- Formulaire d'inscription -->
  <section class="login-section">
    <form method="post" class="login-form" novalidate>
      <h2>Créer un compte</h2>

      <?php if (!empty($erreurs)): ?>
      <div class="erreurs">
        <ul>
          <?php foreach ($erreurs as $err): ?>
          <li><?= htmlspecialchars($err) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="username">Nom d’utilisateur</label>
        <input type="text" id="username" name="username" required placeholder="Votre pseudo" value="<?= htmlspecialchars($pseudo) ?>">
      </div>
      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required placeholder="votre@exemple.com" value="<?= htmlspecialchars($email) ?>">
      </div>
      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required placeholder="••••••••">
      </div>
      <div class="form-group">
        <label for="confirm-password">Confirmez le mot de passe</label>
        <input type="password" id="confirm-password" name="confirm-password" required placeholder="••••••••">
      </div>
      <button type="submit" class="btn">S’inscrire</button>
      <p class="alt-link">
        Déjà inscrit ? <a href="login.php">Se connecter</a>
      </p>
    </form>
  </section>

  <!-- bascule thème -->
  <script src="homepage.js"></script>
</body>
</html>




