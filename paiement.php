<?php
// paiement.php
session_start();

if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Chargement du voyage par ID en GET
$id = intval($_GET['id'] ?? 0);
$chemin = __DIR__ . '/voyages.json';
$data = @file_get_contents($chemin);
$voyages = $data ? json_decode($data, true) : [];
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
  <title>Paiement – <?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></title>
  <link rel="stylesheet" href="detail.css">
  <link rel="stylesheet" href="recap_voyage.css">
 <link rel="stylesheet" href="paiement.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
     <link id="theme-css" rel="stylesheet" href="clair.css">
</head>
<body>
 <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="admin.php">Administrateur</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <section class="recap-section">
    <h1>Paiement</h1>
    <h2><?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></h2>
    <p>Dates : <?= htmlspecialchars($details['date_debut'], ENT_QUOTES) ?> → <?= htmlspecialchars($details['date_fin'], ENT_QUOTES) ?></p>

    <form method="post" action="verifpaiement.php" class="recap-section">
      <input type="hidden" name="id" value="<?= $id ?>">

      <div class="form-group">
        <label for="card_number">Numéro de carte (4 groupes de 4 chiffres)</label>
        <input type="text" id="card_number" name="card_number" placeholder="0000 0000 0000 0000"
               pattern="\d{4} \d{4} \d{4} \d{4}" maxlength="19" required>
      </div>

      <div class="form-group">
        <label for="card_name">Nom et prénom du titulaire</label>
        <input type="text" id="card_name" name="card_name" placeholder="Nom Prénom" required>
      </div>

      <div class="form-row">
        <div class="form-group small">
          <label for="exp_month">Mois (MM)</label>
          <input type="text" id="exp_month" name="exp_month" placeholder="MM" pattern="\d{2}" maxlength="2" required>
        </div>
        <div class="form-group small">
          <label for="exp_year">Année (AA)</label>
          <input type="text" id="exp_year" name="exp_year" placeholder="AA" pattern="\d{2}" maxlength="2" required>
        </div>
        <div class="form-group small">
          <label for="cvv">CVV (3 chiffres)</label>
          <input type="text" id="cvv" name="cvv" placeholder="123" pattern="\d{3}" maxlength="3" required>
        </div>
      </div>

      <button type="submit" class="btn">Valider le paiement</button>
    </form>
  </section>

  <script src="homepage.js"></script>
</body>
</html>


