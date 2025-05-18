<?php
// login.php
session_start();

// Chemin vers le fichier JSON des utilisateurs
$cheminUsers = __DIR__ . '/../info/utilisateurs.json';

// Charger les utilisateurs
$jsonData = @file_get_contents($cheminUsers);
$utilisateurs = $jsonData !== false ? json_decode($jsonData, true) : [];
if (!is_array($utilisateurs)) {
    $utilisateurs = [];
}

$erreurs = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $mdp   = $_POST['password'] ?? '';

    // Validation basique
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreurs[] = 'Adresse e-mail invalide.';
    }
    if ($mdp === '') {
        $erreurs[] = 'Le mot de passe est requis.';
    }

    if (empty($erreurs)) {
        $trouve = false;
        foreach ($utilisateurs as &$u) {
            if (strcasecmp($u['email'], $email) === 0) {
                $trouve = true;
                // Vérifier mot de passe hashé ou clair
                $isHash = strpos($u['mot_de_passe'], '$2y$') === 0;
                $validPassword = $isHash
                    ? password_verify($mdp, $u['mot_de_passe'])
                    : ($mdp === $u['mot_de_passe']);

                if ($validPassword) {
                    // Mettre à jour la dernière connexion
                    $u['derniere_connexion'] = date('Y-m-d H:i:s');
                    file_put_contents(
                        $cheminUsers,
                        json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                    );
                    // Stocker l'utilisateur et son rôle en session
                    $_SESSION['user']  = $u;
                    $_SESSION['login'] = $u['login'];
                    $_SESSION['role']  = $u['role'];
                    header('Location: profil.php');
                    exit;
                } else {
                    $erreurs[] = 'Mot de passe incorrect.';
                }
                break;
            }
        }
        if (!$trouve) {
            $erreurs[] = 'Aucun compte n\'est associé à cette adresse e-mail.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Connexion – CY City Adventure</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="login.css">
   <link id="theme-css" rel="stylesheet" href="clair.css">
</head>
<body>

  <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="presentationn.html">Présentation</a></li>
      <li><a href="voyage.php">Nos destinations</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="admin.php">Administrateur</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <!-- Formulaire de connexion -->
  <section class="login-section">
    <form method="post" class="login-form" novalidate>
      <h2>Se connecter</h2>

      <?php if (!empty($erreurs)): ?>
      <div class="erreurs">
        <ul>
          <?php foreach ($erreurs as $err): ?>
          <li><?= htmlspecialchars($err, ENT_QUOTES) ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
      <?php endif; ?>

      <div class="form-group">
        <label for="email">Adresse e-mail</label>
        <input
          type="email"
          id="email"
          name="email"
          required
          placeholder="votre@exemple.com"
          value="<?= htmlspecialchars($_POST['email'] ?? '', ENT_QUOTES) ?>"
        >
      </div>

      <div class="form-group">
        <label for="password">Mot de passe</label>
        <input
          type="password"
          id="password"
          name="password"
          required
          placeholder="••••••••"
        >
      </div>

      <button type="submit" class="btn">Connexion</button>
      <p class="alt-link">
        Pas encore inscrit ? <a href="inscription.php">Créer un compte</a>
      </p>
    </form>
  </section>

  <script src="homepage.js"></script>
</body>
</html>

