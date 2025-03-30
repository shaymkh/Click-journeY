<?php
// Récupération des voyages
$voyages = json_decode(file_get_contents("data/voyages.json"), true);
$resultats = [];

if ($_SERVER["REQUEST_METHOD"] === "GET" && !empty($_GET)) {
    $destination = $_GET['destination'] ?? '';
    $start = $_GET['start_date'] ?? '';
    $end = $_GET['end_date'] ?? '';
    $option = $_GET['options'] ?? '';

    foreach ($voyages as $voyage) {
        // Filtrage simple par destination
        if ($destination && stripos($voyage['titre'], $destination) === false) continue;

        // Filtrage par date (si définie)
        if ($start && $voyage['date_debut'] < $start) continue;
        if ($end && $voyage['date_fin'] > $end) continue;

        // Filtrage par options (option est une string ici)
        if ($option && !in_array($option, $voyage['options'] ?? [])) continue;

        $resultats[] = $voyage;
    }
} else {
    // Si aucune recherche → tous les voyages par défaut
    $resultats = $voyages;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Voyages</title>
    <link rel="stylesheet" href="destinations.css">
</head>
<body>

<div class="container">
    <h1>Rechercher un Voyage</h1>

    <!-- Formulaire -->
    <form method="GET" class="search-form">
        <div class="form-group">
            <label for="destination">Destination</label>
            <select id="destination" name="destination">
                <option value="">-- Choisir --</option>
                <option value="londres">Londres</option>
                <option value="newyork">New York</option>
                <option value="tanger">Tanger</option>
                <option value="amsterdam">Amsterdam</option>
                <option value="dubai">Dubaï</option>
            </select>
        </div>
        <div class="form-group">
            <label>Dates</label>
            <input type="date" name="start_date">
            <input type="date" name="end_date">
        </div>
        <div class="form-group">
            <label>Options</label>
            <label><input type="checkbox" name="options" value="guide"> Guide touristique</label>
            <label><input type="checkbox" name="options" value="transfert"> Transfert aéroport</label>
        </div>
        <button type="submit">Rechercher</button>
    </form>
</div>

<section class="destinations">
    <h2>Résultats</h2>
    <div class="destination-cards">
        <?php foreach ($resultats as $voyage): ?>
            <div class="destination-card">
                <div class="destination-image" style="background-image: url('<?= htmlspecialchars($voyage['image']) ?>');"></div>
                <h3><?= htmlspecialchars($voyage['titre']) ?></h3>
                <p><?= htmlspecialchars($voyage['description']) ?></p>
            </div>
        <?php endforeach; ?>

        <?php if (empty($resultats)): ?>
            <p>Aucun voyage ne correspond à vos critères.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>
