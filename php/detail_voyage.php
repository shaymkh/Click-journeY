
<?php
session_start();

// Chargement du voyage par ID
$id = intval($_GET['id'] ?? 0);
$cheminVoyages = __DIR__ . '/../info/voyages.json';
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
  <link rel="stylesheet" href="detail.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>

    
  <section class="detail-section">
    <h1><?= htmlspecialchars($details['titre']) ?></h1>
    <p>Du <?= htmlspecialchars($details['date_debut']) ?> au <?= htmlspecialchars($details['date_fin']) ?></p>
    <form method="post" action="recap_voyage.php">
      <input type="hidden" name="id" value="<?= intval($details['id']) ?>">
      <?php foreach ($details['etapes'] as $index => $etape): ?>
        <fieldset>
          <legend><?= htmlspecialchars($etape['titre']) ?></legend>
          <?php foreach ($etape['options'] as $nomOpt => $valeurs): ?>
            <label for="opt_<?= $index ?>_<?= htmlspecialchars($nomOpt) ?>"><?= htmlspecialchars($nomOpt) ?></label>
            <select id="opt_<?= $index ?>_<?= htmlspecialchars($nomOpt) ?>" name="options[<?= $index ?>][<?= htmlspecialchars($nomOpt) ?>]">
              <?php foreach ($valeurs as $val => $prix): ?>
                <option value="<?= htmlspecialchars($val) ?>"><?= htmlspecialchars($val) ?> (+<?= number_format($prix,2,',',' ') ?> €)</option>
              <?php endforeach; ?>
            </select>
          <?php endforeach; ?>
        </fieldset>
      <?php endforeach; ?>
      <button type="submit" class="btn">Voir récapitulatif</button>
    </form>
  </section>

  <script src="homepage.js"></script>
</body>
</html>
