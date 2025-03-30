<?php
// retour_paiement.php
require('getapikey.php');  // Inclut le fichier qui contient la fonction getAPIKey

// Récupérer les informations retournées par CY Bank via les paramètres GET
$transaction = $_GET['transaction'];
$montant = $_GET['montant'];
$vendeur = $_GET['vendeur'];
$status = $_GET['status'];
$control = $_GET['control'];

// Calculer la clé API pour le vendeur
$api_key = getAPIKey($vendeur);

// Calculer la valeur de contrôle attendue
$chaine = $api_key . "#" . $transaction . "#" . $montant . "#" . $vendeur . "#" . $status . "#";
$control_calculé = md5($chaine);

// Vérifier la validité de la réponse
if ($control == $control_calculé) {
    echo "Réponse valide : Paiement " . $status;
} else {
    echo "Réponse invalide";
}
?>
