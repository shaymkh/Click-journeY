<?php
session_start();

// Chemin vers JSON
$cheminUsers = __DIR__ . '/utilisateurs.json';
$utilisateurs = json_decode(@file_get_contents($cheminUsers), true) ?: [];

// Redirection si non connecté
if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Initialisation
$user = &$_SESSION['user'];
$editing = $_GET['edit'] ?? null;
$success = null;
$errors = [];

// Traitement POST d'une mise à jour
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $field = $_POST['field'] ?? '';
    $value = trim($_POST['value'] ?? '');
    // Validation selon champ
    if ($field === 'pseudo') {
        if ($value === '') $errors[] = 'Le pseudo ne peut être vide.';
        // unicité
        foreach ($utilisateurs as $u) {
            if ($u['login'] === $value && $u['login'] !== $user['login']) {
                $errors[] = 'Ce pseudo est déjà utilisé.';
                break;
            }
        }
    } elseif ($field === 'email') {
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail invalide.';
        foreach ($utilisateurs as $u) {
            if (strcasecmp($u['email'], $value) === 0 && $u['email'] !== $user['email']) {
                $errors[] = 'Cet e-mail est déjà utilisé.';
                break;
            }
        }
    } elseif ($field === 'password') {
        $confirm = $_POST['confirm'] ?? '';
        if (strlen($value) < 2) $errors[] = 'Mot de passe trop court (>=2).';
        if ($value !== $confirm) $errors[] = 'La confirmation ne correspond pas.';
        $value = password_hash($value, PASSWORD_DEFAULT);
    }
    // Mise à jour si pas d'erreur
    if (empty($errors)) {
        // Mettre à jour JSON et session
        foreach ($utilisateurs as &$u) {
            if ($u['login'] === $user['login'] && $u['email'] === $_SESSION['user']['email']) {
                if ($field === 'pseudo') {
                    $u['login'] = $_SESSION['user']['login'] = $value;
                } elseif ($field === 'email') {
                    $u['email'] = $_SESSION['user']['email'] = $value;
                } elseif ($field === 'password') {
                    $u['mot_de_passe'] = $value;
                }
                break;
            }
        }
        file_put_contents($cheminUsers, json_encode($utilisateurs, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $success = ucfirst($field) . ' mis à jour avec succès.';
        // Arrêter le mode édition
        $editing = null;
    }
}

// Chargement des paiements et voyages (identique à avant)
$payments = json_decode(@file_get_contents(__DIR__.'/../info/payments.json'), true) ?: [];
$mesPaiements = array_filter($payments, fn($p) => ($p['user'] ?? null) === $user['login']);
$voyages = json_decode(@file_get_contents(__DIR__.'/../info/voyages.json'), true) ?: [];
$voyagesPayes = [];
foreach ($mesPaiements as $p) {
    foreach ($voyages as $v) {
        if ($v['id'] === intval($p['voyage_id'])) {
            $voyagesPayes[$v['id']] = $v;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Mon Profil – CY City Adventure</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="profile.css">
  <link id="theme-css" rel="stylesheet" href="clair.css">
</head>
<body>
<nav class="interface">
  <div class="logo">CY City Adventure</div>
  <ul class="interface-links">
    <li><a href="homepage.html">Accueil</a></li>
    <li><a href="presentationn.html">Présentation</a></li>
    <li><a href="voyage.php">Nos destinations</a></li>
    <li><a href="profil.php">Profil</a></li>
    <?php if ($user['role'] === 'admin'): ?>
      <li><a href="admin.php">Administrateur</a></li>
    <?php endif; ?>
  </ul>
  <button id="theme" class="theme">☀️</button>
</nav>
<section class="profile-section">
  <?php if ($success): ?><div class="success"><?= htmlspecialchars($success) ?></div><?php endif; ?>
  <div class="profile-card">
    <h2>Mon Profil</h2>
    <!-- Pseudo -->
    <div class="profile-item">
      <span class="label">Pseudo</span>
      <?php if ($editing==='pseudo'): ?>
        <form method="post" class="inline-form">
          <input type="hidden" name="field" value="pseudo">
          <input type="text" name="value" value="<?= htmlspecialchars($user['login'], ENT_QUOTES) ?>" required>
          <button type="submit" class="btn">Enregistrer</button>
          <a href="profil.php" class="btn secondary">Annuler</a>
        </form>
      <?php else: ?>
        <span class="value"><?= htmlspecialchars($user['login'], ENT_QUOTES) ?></span>
        <a href="profil.php?edit=pseudo" class="edit-btn" title="Modifier votre pseudo">✏️</a>
      <?php endif; ?>
    </div>
    <!-- E-mail -->
    <div class="profile-item">
      <span class="label">E-mail</span>
      <?php if ($editing==='email'): ?>
        <form method="post" class="inline-form">
          <input type="hidden" name="field" value="email">
          <input type="email" name="value" value="<?= htmlspecialchars($user['email'], ENT_QUOTES) ?>" required>
          <button type="submit" class="btn">Enregistrer</button>
          <a href="profil.php" class="btn secondary">Annuler</a>
        </form>
      <?php else: ?>
        <span class="value"><?= htmlspecialchars($user['email'], ENT_QUOTES) ?></span>
        <a href="profil.php?edit=email" class="edit-btn" title="Modifier votre e-mail">✏️</a>
      <?php endif; ?>
    </div>
    <!-- Mot de passe -->
    <div class="profile-item">
      <span class="label">Mot de passe</span>
      <?php if ($editing==='password'): ?>
        <form method="post" class="inline-form">
          <input type="hidden" name="field" value="password">
          <input type="password" name="value" placeholder="Nouveau mot de passe" required minlength="6">
          <input type="password" name="confirm" placeholder="Confirmer" required minlength="6">
          <button type="submit" class="btn">Enregistrer</button>
          <a href="profil.php" class="btn secondary">Annuler</a>
        </form>
      <?php else: ?>
        <span class="value">••••••••</span>
        <a href="profil.php?edit=password" class="edit-btn" title="Modifier votre mot de passe">✏️</a>
      <?php endif; ?>
    </div>
     </div>
  <div class="history-section">
    <h2>Mes voyages réservés</h2>
    <?php if (empty($voyagesPayes)): ?>
      <p>Aucun voyage réservé pour le moment.</p>
    <?php else: ?>
      <ul class="voyage-list">
        <?php foreach ($voyagesPayes as $v): ?>
          <li>
            <a href="recap_voyage2.php?id=<?= $v['id'] ?>&readonly=1">
              <?= htmlspecialchars($v['titre'], ENT_QUOTES) ?>
              (<?= htmlspecialchars($v['date_debut'], ENT_QUOTES) ?> → <?= htmlspecialchars($v['date_fin'], ENT_QUOTES) ?>)
            </a>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </div>
</section>
    
<script src="homepage.js"></script>
    <script src="profil.js" defer></script>
</body>
</html>


