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
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recherche de voyages – CY City Adventure</title>
  <link rel="stylesheet" href="destinations.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
</head>
<body>
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
</nav>

<section class="search-section">
  <h1>Rechercher un voyage</h1>
  <form class="search-form">
    <div class="field-group">
      <label for="from-date">Date de départ</label>
      <input type="date" id="from-date" name="from-date">
    </div>
    <div class="field-group">
      <label for="to-date">Date de retour</label>
      <input type="date" id="to-date" name="to-date">
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

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.querySelector('.search-form');
  const resultsContainer = document.querySelector('.results');
  const sortBy = document.getElementById('sort-by');
  const btnAsc = document.getElementById('sort-asc');
  const btnDesc = document.getElementById('sort-desc');
  const sortControls = document.querySelector('.sort-controls');
  let tousLesVoyages = [];
  let resultats = [];

  const imagesParId = {
    1: "https://encrypted-tbn2.gstatic.com/licensed-image?q=tbn:ANd9GcQSslEZn6yup0IVIogZvjO1uqpCKVYVPY-i2SXFg7zARRFb3Mlvnq1LIrPilkvAUq-KLOx4ABgO_fsdLDb9hH8iJqkuGw09sDeVxNZRGg",
    2: "https://cdn.getyourguide.com/image/format=auto,fit=crop,gravity=auto,quality=60,width=450,height=450,dpr=2/tour_img/a261cca53a808f1e9bb78aa56a30e4b4f7c2d99fe1b4cd9dafc8d6ef0e68abfe.jpg",
    3: "https://lh3.googleusercontent.com/gps-cs-s/AC9h4nqFlyUm_5_JPZ1a3S5hlOGsLVhG4_gjdxv8EVV6fnPJ61Jc3iFzjYTTaoKUcDtkjBZAs9Q9CucgOmYnlDyIZ6nqiv8qJNgk8G3lq8E_DV5-Ei-HKD_CVx98eh800IPMFxYVWMfSvQ=w1080-h624-n-k-no",
    4: "https://www.nyc.fr/wp-content/uploads/2015/07/New_York_City-scaled.jpg",
    5: "https://www.livreshebdo.fr/sites/default/files/2023-12/New-York.jpeg",
    6: "https://localadventurer.com/wp-content/uploads/2018/12/christmas-decorations-nyc.jpg",
    7: "https://www.barcelo.com/guia-turismo/wp-content/uploads/2022/01/tanger-chefchaouen-888.jpg",
    8: "https://u.profitroom.pl/2020-mogadorhotels-com/thumb/0x1000/uploads/cities/shutterstock_214315636.jpg",
    9: "https://cf.bstatic.com/xdata/images/hotel/max1024x768/474961167.jpg?k=10ac1171669e4f6fe5f39ff38152c8ca20eff9f94ea355348fafa06a1a327a71&o=&hp=1",
    10: "https://cdn.generationvoyage.fr/2023/12/Velos-a-Amsterdam.jpeg",
    11: "https://i0.wp.com/lucile-k.com/wp-content/uploads/2019/04/KendraBen-amsterdam-engagement-session-6.jpg",
    12: "https://media.istockphoto.com/id/1317323730/fr/photo/paysage-rural-n%C3%A9erlandais-avec-des-tulipes-et-un-canal-au-printemps.jpg",
    13: "https://images3.bovpg.net/r/back/fr/sale/5718a686e852ao.jpg",
    14: "https://discover.ulysse.com/wp-content/uploads/2023/12/Desert-de-Dubai-Emirats-arabes-unis-.jpg",
    15: "https://media-cdn.tripadvisor.com/media/attractions-splice-spp-674x446/11/fa/a4/5a.jpg"
  };

  function parseDate(str) {
    const [d, m, y] = str.split('-').map(Number);
    return new Date(y, m - 1, d);
  }

  function render() {
    resultsContainer.innerHTML = '';
    if (resultats.length === 0) {
      resultsContainer.innerHTML = '<p>Aucun résultat.</p>';
      return;
    }
    resultats.forEach(v => {
      const card = document.createElement('div');
      card.className = 'card';
      const duree = Math.round((parseDate(v.date_fin) - parseDate(v.date_debut)) / (1000*60*60*24));
      const image = imagesParId[v.id] || `https://source.unsplash.com/400x300/?${encodeURIComponent(v.titre)}`;
      card.innerHTML = `
        <a href="detail_voyage.php?id=${v.id}" class="card-link">
          <div class="destination-image" style="background-image:url('${image}')"></div>
          <h3>${v.titre}</h3>
          <p>Du ${v.date_debut} au ${v.date_fin} (${duree} jours)</p>
          <p>Étapes : ${v.nb_etapes} – Prix : ${v.prix_base.toFixed(2)} €</p>
        </a>
      `;
      resultsContainer.appendChild(card);
    });
    sortControls.style.display = 'block';
  }

  function filtrerEtAfficher() {
    const from = form['from-date'].value;
    const to = form['to-date'].value;
    const dest = form['destination'].value.toLowerCase();

    resultats = tousLesVoyages.filter(v => {
      const deb = parseDate(v.date_debut);
      const fin = parseDate(v.date_fin);
      let ok = true;
      if (from) ok = ok && deb >= new Date(from);
      if (to) ok = ok && fin <= new Date(to);
      if (dest) ok = ok && v.titre.toLowerCase().includes(dest);
      return ok;
    });
    render();
  }

  function trier(critere, ordre) {
    resultats.sort((a, b) => {
      let cmp = 0;
      if (critere === 'date') cmp = parseDate(a.date_debut) - parseDate(b.date_debut);
      if (critere === 'prix') cmp = a.prix_base - b.prix_base;
      if (critere === 'duree') cmp = parseDate(a.date_fin) - parseDate(a.date_debut) - (parseDate(b.date_fin) - parseDate(b.date_debut));
      if (critere === 'etapes') cmp = a.nb_etapes - b.nb_etapes;
      return ordre === 'desc' ? -cmp : cmp;
    });
    render();
  }

  fetch('voyages.json')
    .then(r => r.json())
    .then(data => {
      tousLesVoyages = data;
      filtrerEtAfficher();
    });

  form.addEventListener('input', filtrerEtAfficher);
  sortBy.addEventListener('change', () => {
    if (sortBy.value) trier(sortBy.value, 'asc');
  });
  btnAsc.addEventListener('click', () => {
    if (sortBy.value) trier(sortBy.value, 'asc');
  });
  btnDesc.addEventListener('click', () => {
    if (sortBy.value) trier(sortBy.value, 'desc');
  });
});
</script>
<script src="homepage.js"></script>
</body>
</html>

