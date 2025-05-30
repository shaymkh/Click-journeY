
<?php
session_start();

// Chargement du voyage par ID
$id = intval($_GET['id'] ?? 0);
$cheminVoyages = __DIR__ . '/voyages.json';
$donnees = @file_get_contents($cheminVoyages);
$voyages = $donnees ? json_decode($donnees, true) : [];
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
if (!$details) {
    die('Voyage non trouvé');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= htmlspecialchars($details['titre']) ?> – CY City Adventure</title>
  <!-- CSS global -->
  <link rel="stylesheet" href="detail.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
  <!-- Thème clair/sombre -->
  <link id="theme-css" rel="stylesheet" href="clair.css">
</head>
<body data-prix-base="<?= $details['prix_base'] ?>">
  <!-- Navigation avec bascule thème -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="voyage.html">Nos destinations</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="profil.php">Profil</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <section class="detail-section">
    <h1><?= htmlspecialchars($details['titre']) ?></h1>
    <p>Du <?= htmlspecialchars($details['date_debut']) ?> au <?= htmlspecialchars($details['date_fin']) ?></p>

    <form method="post" action="recap_voyage.php">
      <input type="hidden" name="id" value="<?= intval($details['id']) ?>">

      <div class="field-group">
        <label for="nb-personnes">Nombre de personnes</label>
        <input type="number" id="nb-personnes" name="nb_personnes" value="1" min="1">
      </div>

      <div id="options-container"></div>

      <button type="submit" class="btn">Voir récapitulatif</button>
    </form>

    <p class="prix-estime">
      Prix estimé : <span id="prix-estime">...</span> €
      <span id="calcul-loader" style="display:none;">⏳ Calcul...</span>
    </p>
  </section>

  <script src="detail_voyage.js"></script>
  <script src="homepage.js"></script>
</body>
</html>
