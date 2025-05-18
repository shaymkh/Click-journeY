<?php
session_start();
// seuls les admins
if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}

// Chemin vers le fichier JSON des utilisateurs
$cheminUsers = __DIR__ . '/../info/utilisateurs.json';
// Lecture du JSON
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

<!-- admin.php -->
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Administration – CY City Adventure</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="admin.css">
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
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <!-- Section Administration -->
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
              <!-- Toggle VIP -->
              <form method="post" action="admin_action.php" style="display:inline;">
                <input type="hidden" name="login" value="<?= htmlspecialchars($u['login']) ?>">
                <button type="submit" name="action" value="toggle_vip" class="action-btn vip-btn" title="Toggle VIP">✅</button>
              </form>
              <!-- Toggle Bannissement -->
              <form method="post" action="admin_action.php" style="display:inline;">
                <input type="hidden" name="login" value="<?= htmlspecialchars($u['login']) ?>">
                <button type="submit" name="action" value="toggle_ban" class="action-btn ban-btn" title="Toggle Bannissement">🚫</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
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

  <!-- Bascule thème -->
  <script src="homepage.js"></script>
</body>
</html>

