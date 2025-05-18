<?php
// confirmation.php
session_start();

// Récupération de l'ID du voyage
$id = intval($_GET['id'] ?? 0);

// Chargement du voyage depuis JSON
$chemin = __DIR__ . '/../info/voyages.json';
$data = @file_get_contents($chemin);
$voyages = $data !== false ? json_decode($data, true) : [];
$details = null;
foreach ($voyages as $v) {
    if (isset($v['id']) && intval($v['id']) === $id) {
        $details = $v;
        break;
    }
}
if (!$details) {
    die('Voyage introuvable');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Paiement confirmé – <?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></title>
  <link rel="stylesheet" href="detail.css">
  <link rel="stylesheet" href="recap_voyage.css">
  <link rel="stylesheet" href="paiement.css">
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
      <li><a href="profil.php">Profil</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <section class="recap-section">
    <h1>Paiement confirmé</h1>
    <p>Merci ! Votre paiement pour le voyage « <strong><?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></strong> » du
       <?= htmlspecialchars($details['date_debut'], ENT_QUOTES) ?> au <?= htmlspecialchars($details['date_fin'], ENT_QUOTES) ?>
       a bien été pris en compte.</p>
    <p>Vous pouvez retrouver vos réservations dans votre <a href="profil.php">espace profil</a>.</p>
    <div class="recap-actions">
      <a href="homepage.html" class="btn">Retour à l'accueil</a>
      <a href="profil.php" class="btn">Mon profil</a>
    </div>
  </section>

  <script src="homepage.js"></script>
</body>
</html>
