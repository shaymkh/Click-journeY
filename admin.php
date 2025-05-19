<?php
session_start();
// seuls les admins
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Chemin vers le fichier JSON des utilisateurs
$cheminUsers = __DIR__ . '/utilisateurs.json';
$json = @file_get_contents($cheminUsers);
$utilisateurs = $json ? json_decode($json, true) : [];
if (!is_array($utilisateurs)) {
    $utilisateurs = [];
}

// Pagination
$parPage = 10;
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$total = count($utilisateurs);
$pages = ceil($total / $parPage);
$offset = ($page - 1) * $parPage;
$liste = array_slice($utilisateurs, $offset, $parPage);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administration – CY City Adventure</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
  <link id="theme-css" rel="stylesheet" href="clair.css">
</head>
<body>

  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="voyage.php">Nos destinations</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <section class="admin-section">
    <h1>Tableau de bord Administrateur</h1>
    <div class="table-container">
      <table class="users-table">
        <thead>
          <tr>
            <th>Nom</th>
            <th>E-mail</th>
            <th>VIP</th>
            <th>Banni</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($liste as $u): ?>
          <tr>
            <td><?= htmlspecialchars($u['login']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>
            <td><span class="status <?= (!empty($u['vip']) ? 'yes' : 'no') ?>"><?= (!empty($u['vip']) ? 'Oui' : 'Non') ?></span></td>
            <td><span class="status <?= (!empty($u['banni']) ? 'yes' : 'no') ?>"><?= (!empty($u['banni']) ? 'Oui' : 'Non') ?></span></td>
            <td>
              <button class="btn-action" data-login="<?= htmlspecialchars($u['login']) ?>" data-type="vip" title="Basculer VIP">✅</button>
              <button class="btn-action" data-login="<?= htmlspecialchars($u['login']) ?>" data-type="banni" title="Basculer Banni">🚫</button>
              <span class="loader" style="display:none;">⏳</span>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="pagination">
      <?php for ($i = 1; $i <= $pages; $i++): ?>
        <?php if ($i === $page): ?>
          <span class="current"><?= $i ?></span>
        <?php else: ?>
          <a href="?page=<?= $i ?>"><?= $i ?></a>
        <?php endif; ?>
      <?php endfor; ?>
    </div>
  </section>

  <script src="homepage.js"></script>
  <script src="admin.js" defer></script>
</body>
</html>
