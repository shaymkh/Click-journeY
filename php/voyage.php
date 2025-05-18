<?php
// destinations.php
session_start();

// Lecture du fichier JSON des voyages
$cheminVoyages = __DIR__ . '/../info/voyages.json';
$donnees = @file_get_contents($cheminVoyages);
$voyages = $donnees ? json_decode($donnees, true) : [];
if (!is_array($voyages)) {
    $voyages = [];
}

// Mapping spécifique d'images pour certains voyages
$imagesSpecifiques = [
    1  => 'https://encrypted-tbn2.gstatic.com/licensed-image?q=tbn:ANd9GcQSslEZn6yup0IVIogZvjO1uqpCKVYVPY-i2SXFg7zARRFb3Mlvnq1LIrPilkvAUq-KLOx4ABgO_fsdLDb9hH8iJqkuGw09sDeVxNZRGg', // Escapade à Londres (id=1)
    2  => 'https://cdn.getyourguide.com/image/format=auto,fit=crop,gravity=auto,quality=60,width=450,height=450,dpr=2/tour_img/a261cca53a808f1e9bb78aa56a30e4b4f7c2d99fe1b4cd9dafc8d6ef0e68abfe.jpg', // Weekend Royal à Londres (id=2)
    3  => 'https://lh3.googleusercontent.com/gps-cs-s/AC9h4nqFlyUm_5_JPZ1a3S5hlOGsLVhG4_gjdxv8EVV6fnPJ61Jc3iFzjYTTaoKUcDtkjBZAs9Q9CucgOmYnlDyIZ6nqiv8qJNgk8G3lq8E_DV5-Ei-HKD_CVx98eh800IPMFxYVWMfSvQ=w1080-h624-n-k-no', // Tour Culturel de Londres (id=3)
    4  => 'https://www.nyc.fr/wp-content/uploads/2015/07/New_York_City-scaled.jpg', // Week-end à New York Classique (id=4)
    5  => 'https://www.livreshebdo.fr/sites/default/files/2023-12/New-York.jpeg', // City-pass New York (id=5)
    6  => 'https://localadventurer.com/wp-content/uploads/2018/12/christmas-decorations-nyc.jpg', // Noël à New York (id=6)
    7  => 'https://www.barcelo.com/guia-turismo/wp-content/uploads/2022/01/tanger-chefchaouen-888.jpg', // Découverte de Tanger Basique (id=7)
    8  => 'https://u.profitroom.pl/2020-mogadorhotels-com/thumb/0x1000/uploads/cities/shutterstock_214315636.jpg', // Festival de Tanger (id=8)
    9  => 'https://cf.bstatic.com/xdata/images/hotel/max1024x768/474961167.jpg?k=10ac1171669e4f6fe5f39ff38152c8ca20eff9f94ea355348fafa06a1a327a71&o=&hp=1', // Circuit Méditerranée & Tanger (id=9)
    10 => 'https://cdn.generationvoyage.fr/2023/12/Velos-a-Amsterdam.jpeg', // Balade à Amsterdam (id=10)
   11 => 'https://i0.wp.com/lucile-k.com/wp-content/uploads/2019/04/KendraBen-amsterdam-engagement-session-6.jpg', // Week-end Romantique Amsterdam (id=11)
   12 => 'https://img.pixers.pics/pho_wat(s3:700/FO/90/22/71/93/700_FO90227193_96e5148498d14cb19ca086f2dc13cc57.jpg,628,700,cms:2018/10/5bd1b6b8d04b8_220x50-watermark.png,over,408,650,jpg)/coussins-decoratifs-beau-paysage-avec-des-tulipes-et-des-maisons-a-amsterdam-pays-bas.jpg.jpg', // Tulipes & Canaux (id=12)
   13 => 'https://images3.bovpg.net/r/back/fr/sale/5718a686e852ao.jpg', // Séjour Luxe à Dubaï (id=13)
   14 => 'https://discover.ulysse.com/wp-content/uploads/2023/12/Desert-de-Dubai-Emirats-arabes-unis-.jpg', // Aventure Désert & Dubaï (id=14)
   15 => 'https://media-cdn.tripadvisor.com/media/attractions-splice-spp-674x446/11/fa/a4/5a.jpg', // Expo Dubaï (id=15)
];

// Sélection de 3 voyages aléatoires
$aleatoires = $voyages;
shuffle($aleatoires);
$selectionAleatoire = array_slice($aleatoires, 0, 3);

// Sélection des 3 mieux notés (prix de base décroissant)
usort($voyages, function($a, $b) {
    return $b['prix_base'] <=> $a['prix_base'];
});
$meilleurs = array_slice($voyages, 0, 3);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Destinations – CY City Adventure</title>
  <link rel="stylesheet" href="destinations.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
  <!-- Navigation -->
  <nav class="interface">
    <div class="logo">CY City Adventure</div>
    <ul class="interface-links">
      <li><a href="homepage.html">Accueil</a></li>
      <li><a href="presentationn.html">Présentation</a></li>
      <li><a href="inscription.php">S'inscrire</a></li>
      <li><a href="login.php">Se connecter</a></li>
      <li><a href="profil.php">Profil</a></li>
      <li><a href="admin.php">Administrateur</a></li>
    </ul>
    <button id="theme" class="theme">☀️</button>
  </nav><!-- Barre de recherche avancée -->
<section class="search-section">
  <form method="get" class="search-form">
    <div class="field-group">
      <label for="q">Mot-clé</label>
      <input type="text" id="q" name="q" placeholder="Rechercher un voyage…" class="search-input" value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
    </div>
    <div class="field-group">
      <label for="from-date">Date de départ</label>
      <input type="date" id="from-date" name="from-date" value="<?= htmlspecialchars($_GET['from-date'] ?? '') ?>">
    </div>
    <div class="field-group">
      <label for="to-date">Date de retour</label>
      <input type="date" id="to-date" name="to-date" value="<?= htmlspecialchars($_GET['to-date'] ?? '') ?>">
    </div>
    <div class="field-group">
      <label for="lieu">Lieu</label>
      <select id="lieu" name="lieu">
        <option value="">Tous</option>
        <option value="Londres" <?= (($_GET['lieu'] ?? '') === 'Londres') ? 'selected' : '' ?>>Londres</option>
        <option value="New York" <?= (($_GET['lieu'] ?? '') === 'New York') ? 'selected' : '' ?>>New York</option>
        <option value="Amsterdam" <?= (($_GET['lieu'] ?? '') === 'Amsterdam') ? 'selected' : '' ?>>Amsterdam</option>
        <option value="Tanger" <?= (($_GET['lieu'] ?? '') === 'Tanger') ? 'selected' : '' ?>>Tanger</option>
        <option value="Dubai" <?= (($_GET['lieu'] ?? '') === 'Dubai') ? 'selected' : '' ?>>Dubaï</option>
      </select>
    </div>
    <button type="submit" class="btn">Filtrer</button>
  </form>
</section>

<!-- Sélection aléatoire -->
  <section class="dest-preview">
    <h2>Sélection aléatoire</h2>
    <div class="cards-dest">
      <?php foreach ($selectionAleatoire as $v): ?>
      <?php $img = $imagesSpecifiques[$v['id']] ?? "https://source.unsplash.com/400x300/?".urlencode($v['titre']); ?>
      <div class="destination-card">
        <a href="detail_voyage.php?id=<?= intval($v['id']) ?>">
          <div class="destination-image" style="background-image:url('<?= htmlspecialchars($img) ?>')"></div>
          <h3><?= htmlspecialchars($v['titre']) ?></h3>
          <p>Du <?= htmlspecialchars($v['date_debut']) ?> au <?= htmlspecialchars($v['date_fin']) ?></p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Les mieux notés -->
  <section class="dest-preview">
    <h2>Les mieux notés</h2>
    <div class="cards-dest">
      <?php foreach ($meilleurs as $v): ?>
      <?php $img = $imagesSpecifiques[$v['id']] ?? "https://source.unsplash.com/400x300/?".urlencode($v['titre']); ?>
      <div class="destination-card">
        <a href="detail_voyage.php?id=<?= intval($v['id']) ?>">
          <div class="destination-image" style="background-image:url('<?= htmlspecialchars($img) ?>')"></div>
          <h3><?= htmlspecialchars($v['titre']) ?></h3>
          <p>À partir de <?= number_format($v['prix_base'], 2, ',', ' ') ?> €</p>
        </a>
      </div>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- Bascule thème -->
  <script src="homepage.js"></script>
</body>
</html>


