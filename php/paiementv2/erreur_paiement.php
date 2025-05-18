<?php
// erreur_paiement.php
session_start();

// Récupération de l'ID du voyage
$id = intval($_GET['id'] ?? 0);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Paiement refusé – CY City Adventure</title>
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
      <li><a href="voyage.php">Voyages</a></li>
      <li><a href="profil.php">Profil</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav>

  <section class="recap-section">
    <h1>Paiement refusé</h1>
    <p>Le paiement n’a pas pu être validé. Cela peut être dû à des informations bancaires incorrectes ou un solde insuffisant.</p>
    <div class="recap-actions">
      <a href="paiement.php?id=<?= $id ?>" class="btn">Retenter le paiement</a>
      <a href="recap_voyage.php?id=<?= $id ?>" class="btn">Retour au récapitulatif</a>
    </div>
  </section>

  <script src="homepage.js"></script>
</body>
</html>
