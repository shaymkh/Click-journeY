<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CY City - Agence de voyage urbaine</title>
    <link rel="stylesheet" href="presentation.css">
</head>
<body>

    <div class="container">
        <h1>🏙️ Bienvenue sur CY City adventure, votre agence de voyage spécialisée en destinations urbaines !</h1>
        <p>Partez à la découverte des grandes villes du monde avec CY City ! De Dubaï à New York, en passant par Londres, Amsterdam et Tanger, nous concevons des voyages sur mesure pour vous faire vivre des expériences uniques au cœur des métropoles les plus fascinantes.</p>

        <div class="search-box">
            <p>✈️ Trouvez votre prochaine destination :</p>
            <form method="GET" action="destinations.php">
                <input type="text" name="destination" placeholder="Où souhaitez-vous partir ?">
                <button type="submit">Rechercher</button>
            </form>
        </div>

        <div class="sidebar">
            <h2>Cy City Adventure</h2>
            <ul>
                <li><a href="accueil.php">🏠 Accueil</a></li>
                <li><a href="presentation.php">📜 Présentation</a></li>

                <?php if (!isset($_SESSION['utilisateur'])): ?>
                    <li><a href="connexion.php">🔑 Se connecter</a></li>
                    <li><a href="inscription.php">📝 Inscription</a></li>
                <?php else: ?>
                    <li><a href="modifier_profil.php">✏️ Modifier votre profil</a></li>
                    <?php if ($_SESSION['utilisateur']['role'] === 'admin'): ?>
                        <li><a href="admin.php">👨‍💻 Administrateur</a></li>
                    <?php endif; ?>
                    <li><a href="deconnexion.php">🚪 Déconnexion</a></li>
                <?php endif; ?>

                <li><a href="destinations.php">🌍 Destinations</a></li>
            </ul>
        </div>

        <div class="destinations">
            <p>🌍 <strong>New York</strong> – La ville qui ne dort jamais</p>
            <p>🌆 <strong>Dubaï</strong> – Luxe et modernité</p>
            <p>🌐 <strong>Londres</strong> – Entre histoire et tendance</p>
            <p>🇳🇱 <strong>Amsterdam</strong> – Canaux et culture</p>
            <p>🇲🇦 <strong>Tanger</strong> – Entre Orient et Occident</p>
        </div>

        <p><strong>Rêvez, explorez, voyagez avec CY City !</strong></p>
    </div>

</body>
</html>

