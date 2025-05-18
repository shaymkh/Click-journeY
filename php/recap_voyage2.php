<?php
session_start();

$readonly = isset($_GET['readonly']);

$id = 0;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
} elseif (isset($_POST['id'])) {
    $id = intval($_POST['id']);
}

$options = [];
if (isset($_POST['options']) && is_array($_POST['options'])) {
    $options = $_POST['options'];
}

$nbPersonnes = max(1, intval($_POST['nb_personnes'] ?? 1));

$chemin = __DIR__ . '/voyages.json';
$data = @file_get_contents($chemin);
$voyages = $data !== false ? json_decode($data, true) : [];
if (!is_array($voyages)) {
    die('Erreur de parsing JSON voyages');
}

$details = null;
foreach ($voyages as $v) {
    if (isset($v['id']) && intval($v['id']) === $id) {
        $details = $v;
        break;
    }
}
if (empty($details)) {
    die('Voyage introuvable');
}

$total = floatval($details['prix_base']);
foreach ($details['etapes'] as $i => $etape) {
    foreach ($etape['options'] as $nomOpt => $valeurs) {
        $choix = $options[$i][$nomOpt] ?? $etape['options_defaut'][$nomOpt] ?? null;
        if ($choix !== null && isset($valeurs[$choix])) {
            $total += floatval($valeurs[$choix]);
        }
    }
}
$total *= $nbPersonnes;

$dateDebut = new DateTime($details['date_debut']);
$dateFin = new DateTime($details['date_fin']);
$durationDays = $dateDebut->diff($dateFin)->days;
$nbEtapes = count($details['etapes']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Récapitulatif – <?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></title>
  <link rel="stylesheet" href="destinations.css">
  <link rel="stylesheet" href="detail.css">
  <link rel="stylesheet" href="recap_voyage.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body<?= $readonly ? ' class="readonly"' : '' ?>>

  <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="presentationn.html">Présentation</a></li>
      <li><a href="voyage.php">Voyages</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="admin.php">Administrateur</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <!-- Récapitulatif -->
  <section class="recap-section">
    <h1>Récapitulatif de votre voyage</h1>
    <h2><?= htmlspecialchars($details['titre'], ENT_QUOTES) ?></h2>
    <p>Dates : <?= htmlspecialchars($details['date_debut'], ENT_QUOTES) ?> → <?= htmlspecialchars($details['date_fin'], ENT_QUOTES) ?></p>
    <p>Durée : <?= $durationDays ?> jours</p>
    <p>Nombre d'étapes : <?= $nbEtapes ?></p>
    <p>Nombre de personnes : <?= $nbPersonnes ?></p>

    <ul>
      <?php foreach ($details['etapes'] as $i => $etape): ?>
        <li>
          <strong><?= htmlspecialchars($etape['titre'], ENT_QUOTES) ?> :</strong>
          <?php foreach ($etape['options'] as $nomOpt => $valeurs): ?>
            <?= htmlspecialchars($nomOpt, ENT_QUOTES) ?> = <?= htmlspecialchars($options[$i][$nomOpt] ?? $etape['options_defaut'][$nomOpt], ENT_QUOTES) ?>;
          <?php endforeach; ?>
        </li>
      <?php endforeach; ?>
    </ul>

    <p><strong>Prix total : <?= number_format($total, 2, ',', ' ') ?> €</strong></p>
  </section>

  <script src="homepage.js"></script>
</body>
</html>
