<?php
session_start();
$cheminVoyages = __DIR__ . '/voyages.json';
$donnees = @file_get_contents($cheminVoyages);
$voyages = $donnees ? json_decode($donnees, true) : [];
if (!is_array($voyages)) $voyages = [];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Recherche de voyages – CY City Adventure</title>
  <link rel="stylesheet" href="destinations.css" />
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet" />
</head>
<body>
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

  <section class="search-section">
    <h1>Rechercher un voyage</h1>
    <form class="search-form">
      <div class="field-group">
        <label for="from-date">Date de départ</label>
        <input type="date" id="from-date" name="from-date" />
      </div>
      <div class="field-group">
        <label for="to-date">Date de retour</label>
        <input type="date" id="to-date" name="to-date" />
      </div>
      <div class="field-group">
        <label for="destination">Destination</label>
        <select id="destination" name="destination">
          <option value="">Sélectionnez...</option>
          <option value="Londres">Londres</option>
          <option value="New York">New York</option>
          <option value="Tanger">Tanger</option>
          <option value="Amsterdam">Amsterdam</option>
          <option value="Dubai">Dubaï</option>
        </select>
      </div>
    </form>

    <div class="sort-controls" style="display:none;">
      <label for="sort-by">Trier par :</label>
      <select id="sort-by">
        <option value="">--</option>
        <option value="date">Date de départ</option>
        <option value="prix">Prix</option>
        <option value="duree">Durée</option>
        <option value="etapes">Étapes</option>
      </select>
      <button id="sort-asc" type="button">Ascendant</button>
      <button id="sort-desc" type="button">Descendant</button>
    </div>

    <div class="results"></div>
  </section>

  <script src="filtrage.js"></script>
  <script src="homepage.js"></script>
</body>
</html>
