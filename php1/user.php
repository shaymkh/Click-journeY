<?php
session_start();

// 🔒 Protection : accès uniquement aux administrateurs
if (!isset($_SESSION['utilisateur']) || $_SESSION['utilisateur']['role'] !== 'admin') {
    header('Location: connexion.php');
    exit;
}

// 📥 Chargement des utilisateurs
$file = 'data/utilisateurs.json';
$utilisateurs = [];

if (file_exists($file)) {
    $utilisateurs = json_decode(file_get_contents($file), true);
    if (!is_array($utilisateurs)) {
        $utilisateurs = [];
    }
}

// 🧠 Fonction pour calculer l’âge
function calculerAge($dateNaissance) {
    $date = DateTime::createFromFormat('Y-m-d', $dateNaissance);
    if (!$date) return 'N/A';
    $now = new DateTime();
    return $now->diff($date)->y;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="user.css">
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
        <h1>Liste des Utilisateurs</h1>

        <!-- Bouton Ajouter (non fonctionnel ici mais stylé) -->
        <button id="addUserBtn">➕ Enregistrer un utilisateur</button>

        <table>
            <thead>
                <tr>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Âge</th>
                    <th>Email</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody id="userTable">
                <?php foreach ($utilisateurs as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['firstName']) ?></td>
                        <td><?= htmlspecialchars($user['lastName']) ?></td>
                        <td><?= calculerAge($user['dob']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['statut'] ?? $user['role'] ?? 'utilisateur') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>
 
