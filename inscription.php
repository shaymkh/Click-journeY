<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // Récupération des données du formulaire
    $address = $_POST['address'];
    $lastName = $_POST['lastName'];
    $firstName = $_POST['firstName'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];

    // Validation des champs
    if (empty($address) || empty($lastName) || empty($firstName) || empty($phone) || empty($email) || empty($password) || empty($dob)) {
        $error = "Tous les champs doivent être remplis.";
    } else {
        // Vérification de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "L'email n'est pas valide.";
        } else {
            // Vérification si l'email existe déjà dans le fichier
            $file = 'users.json';
            if (file_exists($file)) {
                $users = json_decode(file_get_contents($file), true);
                foreach ($users as $user) {
                    if ($user['email'] === $email) {
                        $error = "Cet email est déjà utilisé.";
                        break;
                    }
                }
            }

            // Si l'email est valide et unique, procéder à l'enregistrement
            if (!isset($error)) {
                
                // Préparation des données de l'utilisateur sous forme de tableau
                $userData = [
                    'address' => "$address",
                    'lastName' => "$lastName",
                    'firstName' => "$firstName",
                    'phone' => "$phone",
                    'email' => $email,
                    'dob' => $dob,
                  
                ];

                // Récupérer les utilisateurs existants problème a régler fichier json existant , les donnes preexistantes n'apparaissent pas
                if (file_exists($file)) {
                    $users = json_decode(file_get_contents($file), true);
                } else {
                    $users = [];
                }

                // Ajouter le nouvel utilisateur au tableau
                $users[] = $userData;

                // Sauvegarder les données mises à jour dans le fichier JSON
                if (file_put_contents($file, json_encode($users, JSON_PRETTY_PRINT))) {
                    $success = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                } 
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire d'inscription</title>
    <link rel="stylesheet" href="inscription.css">
</head>
<body>
    <div class="sidebar">
        <h2>Cy City Adventure</h2>
        <ul>
            <li><a href="accueil.html">🏠 Accueil</a></li>
            <li><a href="présentation.html">📜 Présentation</a></li>
            <li><a href="connexion.html">🔑 Se connecter</a></li>
            <li><a href="modifier_profil.html">✏️ Modifier votre profil</a></li>
            <li><a href="destinations.html">🌍 Destinations</a></li>
            <li><a href="admin.html">👨‍💻 Administrateur</a></li>
        </ul>
    </div>

    <div class="form-container">
        <h1>Inscription</h1>

        <!-- Affichage des messages d'erreur ou de succès -->
        <?php if (isset($error)): ?>
            <p style="color: red; font-weight: bold;"><?= $error ?></p>
        <?php endif; ?>
        <?php if (isset($success)): ?>
            <p style="color: green; font-weight: bold;"><?= $success ?></p>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="form-group">
                <label for="phone">Numéro de téléphone</label>
                <input type="tel" id="phone" name="phone" placeholder="Entrez votre numéro" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Entrez votre email" required>
            </div>
            <div class="form-group">
                <label for="lastName">Nom</label>
                <input type="text" id="lastName" name="lastName" placeholder="Entrez votre nom" required>
            </div>
            <div class="form-group">
                <label for="firstName">Prénom</label>
                <input type="text" id="firstName" name="firstName" placeholder="Entrez votre prénom" required>
            </div>
            <div class="form-group">
                <label for="dob">Date de naissance</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="address">Adresse</label>
                <textarea id="address" name="address" rows="4" placeholder="Entrez votre adresse" required></textarea>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Créez un mot de passe" required>
            </div>
            <div class="form-group">
                <button type="submit">S'inscrire</button>
            </div>
        </form>
    </div>

</body>
</html>


