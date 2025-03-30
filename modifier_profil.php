<?php
// Démarre la session
session_start();

// Récupération des utilisateurs depuis le fichier data/utilisateurs.json
$file = 'data/utilisateurs.json';
if (file_exists($file)) {
    $data = json_decode(file_get_contents($file), true);
} else {
    $data = [];
}

// Simulons la récupération de l'utilisateur connecté
$current_user = null;
if (isset($data) && !empty($data)) {
    // Exemple: on prend le premier utilisateur pour cet exemple
    $current_user = $data[0]; // À remplacer par la logique réelle de session si nécessaire
}

// Variable pour le message d'erreur
$error_message = "";

// Vérification du mot de passe avant la modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $password = $_POST['password'];

    // Vérifier si le mot de passe est correctement défini dans le JSON
    if (isset($current_user['password']) && !empty($current_user['password'])) {
        // Comparer directement le mot de passe en clair
        if ($password === $current_user['password']) {
            // Le mot de passe est correct, on peut procéder à la mise à jour des données utilisateur
            $updated_user = [
                'firstName' => $_POST['firstName'] ?? $current_user['firstName'],
                'lastName' => $_POST['lastName'] ?? $current_user['lastName'],
                'email' => $_POST['email'] ?? $current_user['email'],
                'phone' => $_POST['phone'] ?? $current_user['phone'],
                'dob' => $_POST['dob'] ?? $current_user['dob'],
                'address' => $_POST['address'] ?? $current_user['address'],
                'password' => $current_user['password'], // Ne pas modifier le mot de passe ici
            ];

            // Mettre à jour les données utilisateur dans le fichier JSON
            $data[0] = $updated_user;

            // Vérifiez si l'écriture dans le fichier a réussi
            if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT)) !== false) {
                echo "Les données ont été sauvegardées avec succès!";
            } else {
                echo "Une erreur est survenue lors de la sauvegarde des données.";
            }

            // Rafraîchir l'utilisateur après la modification
            $current_user = $updated_user;
        } else {
            $error_message = "Mot de passe incorrect."; // Message d'erreur
        }
    } else {
        $error_message = "Le mot de passe de l'utilisateur n'est pas défini."; // Message d'erreur
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mon Profil</title>
    <link rel="stylesheet" href="modifier_profil.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Pour les icônes -->
</head>
<body>
    <div class="sidebar">
        <h2>Cy City Adventure</h2>
        <ul>
            <li><a href="accueil.php">🏠 Accueil</a></li>
            <li><a href="presentation.php">📜 Présentation</a></li>
            <li><a href="connexion.php">🔑 Se connecter</a></li>
            <li><a href="inscription.php">✏️ inscription</a></li>
            <li><a href="destinations.php">🌍 Destinations</a></li>
            <li><a href="admin.php">👨‍💻 Administrateur</a></li>
        </ul>
    </div>

    <div class="content">
        <h1>Mon Profil</h1>

        <!-- Affichage du message d'erreur -->
        <?php if ($error_message): ?>
            <div class="error-message">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <div class="profil-container">
            <div class="profil-info">
                <div class="profil-field">
                    <label>Nom :</label>
                    <span id="nom"><?php echo $current_user['lastName'] ?? 'Inconnu'; ?></span>
                    <button class="edit-btn" onclick="modifierChamp('nom')"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="profil-field">
                    <label>Prénom :</label>
                    <span id="prenom"><?php echo $current_user['firstName'] ?? 'Inconnu'; ?></span>
                    <button class="edit-btn" onclick="modifierChamp('prenom')"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="profil-field">
                    <label>Email :</label>
                    <span id="email"><?php echo $current_user['email'] ?? 'Inconnu'; ?></span>
                    <button class="edit-btn" onclick="modifierChamp('email')"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="profil-field">
                    <label>Téléphone :</label>
                    <span id="telephone"><?php echo $current_user['phone'] ?? 'Inconnu'; ?></span>
                    <button class="edit-btn" onclick="modifierChamp('telephone')"><i class="fas fa-pencil-alt"></i></button>
                </div>
                <div class="profil-field">
                    <label>Adresse :</label>
                    <span id="adresse"><?php echo $current_user['address'] ?? 'Inconnu'; ?></span>
                    <button class="edit-btn" onclick="modifierChamp('adresse')"><i class="fas fa-pencil-alt"></i></button>
                </div>
            </div>

            <!-- Zone de modification des champs -->
            <div id="form-modifier" class="form-modifier">
                <h2>Modifier Profil</h2>
                <form id="modifier-form" method="POST">
                    <div class="form-group">
                        <label for="new-nom">Nom :</label>
                        <input type="text" id="new-nom" name="lastName" value="<?php echo $current_user['lastName'] ?? ''; ?>" placeholder="Entrez votre nom">
                    </div>
                    <div class="form-group">
                        <label for="new-prenom">Prénom :</label>
                        <input type="text" id="new-prenom" name="firstName" value="<?php echo $current_user['firstName'] ?? ''; ?>" placeholder="Entrez votre prénom">
                    </div>
                    <div class="form-group">
                        <label for="new-email">Email :</label>
                        <input type="email" id="new-email" name="email" value="<?php echo $current_user['email'] ?? ''; ?>" placeholder="Entrez votre email">
                    </div>
                    <div class="form-group">
                        <label for="new-telephone">Téléphone :</label>
                        <input type="tel" id="new-telephone" name="phone" value="<?php echo $current_user['phone'] ?? ''; ?>" placeholder="Entrez votre téléphone">
                    </div>
                    <div class="form-group">
                        <label for="new-adresse">Adresse :</label>
                        <input type="text" id="new-adresse" name="address" value="<?php echo $current_user['address'] ?? ''; ?>" placeholder="Entrez votre adresse">
                    </div>
                    <div class="form-group">
                        <label for="new-mot-de-passe">Mot de passe :</label>
                        <input type="password" id="new-mot-de-passe" name="password" placeholder="Entrez votre mot de passe" required>
                    </div>
                    <button type="submit" name="submit" class="save-btn">Sauvegarder</button>
                    <button type="button" onclick="annulerModification()">Annuler</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function modifierChamp(champ) {
            document.getElementById("form-modifier").style.display = "block";
        }

        function annulerModification() {
            document.getElementById("form-modifier").style.display = "none";
        }
    </script>
</body>
</html>



