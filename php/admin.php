<?php
session_start();

// Vérifier si l'utilisateur est connecté et admin
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['statut'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// Charger les données des utilisateurs (exemple en JSON)
$utilisateurs = json_decode(file_get_contents('data/utilisateurs.json'), true);
$nbUtilisateurs = count($utilisateurs);

// Exemple de valeur fictive
$nbCommentaires = 220;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin.css">
      <script src="admin.js" defer></script>
    <link rel="stylesheet" href="accueil.css">
    <link rel="stylesheet" href="theme.css">
</head>
<body>

    <div class="sidebar">
        <h2>👨‍💻 Administrateur</h2>
        <ul>
            <li><a href="accueil.php">🏠 Accueil</a></li>
            <li><a href="admin_utilisateurs.php">👤 Utilisateurs</a></li>
            <li><a href="#">📂 Contenu</a></li>
            <li><a href="#">⚙️ Paramètres</a></li>
            <li><a href="deconnexion.php">🚪 Déconnexion</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Bienvenue, <?= htmlspecialchars($_SESSION['utilisateur']['pseudo']) ?> !</h1>
        <div class="dashboard">
            <div class="card">
                <h3>Utilisateurs</h3>
                <p><?= $nbUtilisateurs ?></p>
            </div>
            
            <div class="card">
                <h3>Commentaires</h3>
                <p><?= $nbCommentaires ?></p>
            </div>
        </div>
    </div>

</body>
</html>
🔒 deconnexion.php (à créer)
php
Copier
Modifier
<?php
session_start();
session_destroy();
header('Location: connexion.php');
exit;
🗃️ Données utilisateur (utilisateurs.json) – exemple mini
json
Copier
Modifier
[
    {
        "login": "admin1",
        "motdepasse": "$2y$10$.....", 
        "role": "admin",
        "pseudo": "AdminOne"
    },
    {
        "login": "user1",
        "motdepasse": "$2y$10$.....", 
        "role": "utilisateur",
        "pseudo": "Voyageur1"
    }
]
