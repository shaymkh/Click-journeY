<?php
// index.php
require('getapikey.php');  // Inclut le fichier qui contient la fonction getAPIKey

// Données de la transaction
$transaction = '154632ABCD';  // Identifiant de transaction
$montant = '18000.99';        // Montant de la transaction
$vendeur = 'MI-1_G';          // Code vendeur
$retour = 'http://localhost/retour_paiement.php?session=s';  // URL de retour

// Récupérer la clé API pour le vendeur
$api_key = getAPIKey($vendeur);

// Calculer la valeur de contrôle
$chaine = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $retour . "#";
$control = md5($chaine);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Paiement</title>
</head>
<body>
    <h1>Formulaire de Paiement</h1>
    <form action="https://www.plateforme-smc.fr/cybank/index.php" method="POST">
        <input type="hidden" name="transaction" value="<?php echo $transaction; ?>">
        <input type="hidden" name="montant" value="<?php echo $montant; ?>">
        <input type="hidden" name="vendeur" value="<?php echo $vendeur; ?>">
        <input type="hidden" name="retour" value="<?php echo $retour; ?>">
        <input type="hidden" name="control" value="<?php echo $control; ?>">  <!-- Valeur de contrôle générée -->
        <input type="submit" value="Valider et payer">
    </form>
</body>
</html>
