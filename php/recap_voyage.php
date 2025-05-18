<?php
session_start();

// Récupération de l'ID et des options personnalisées
$id = intval($_POST['id'] ?? 0);
$options = $_POST['options'] ?? [];

// Chargement des voyages depuis JSON
$chemin = __DIR__ . '/../info/voyages.json';
$data = @file_get_contents($chemin);
$voyages = $data !== false ? json_decode($data, true) : [];
if (!is_array($voyages)) {
    die('Erreur de parsing JSON voyages');
}

// Recherche du voyage sélectionné
$details = null;
foreach ($voyages as $v) {
    if ($v['id'] === $id) {
        $details = $v;
        break;
    }
}
if (empty($details)) {
    die('Voyage introuvable');
}

// Calcul du prix total
$total = floatval($details['prix_base']);
foreach ($details['etapes'] as $i => $etape) {
    foreach ($etape['options'] as $nomOpt => $valeurs) {
        $choix = $options[$i][$nomOpt] ?? $etape['options_defaut'][$nomOpt] ?? null;
        if ($choix !== null && isset($valeurs[$choix])) {
            $total += floatval($valeurs[$choix]);
        }
    }
}

// Calcul de la durée en jours
$dateDebut = new DateTime($details['date_debut']);
$dateFin = new DateTime($details['date_fin']);
$interval = $dateDebut->diff($dateFin);
$durationDays = $interval->days;

// Nombre d'étapes
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
<body>
  <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.php">Accueil</a></li>
      <li><a href="presentation.php">Présentation</a></li>
      <li><a href="destinations.php">Voyages</a></li>
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

    <div class="recap-actions">
      <a href="detail_voyage.php?id=<?= $id ?>" class="btn">Modifier</a>
      <a href="paiement.php" class="btn">Confirmer et payer</a>
    </div>
  </section>

  <script src="homepage.js"></script>
</body>
</html>


