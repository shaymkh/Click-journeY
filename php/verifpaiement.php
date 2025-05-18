<?php
// verification_paiement.php
session_start();

// Récupération des données du formulaire CB
$id = intval($_POST['id'] ?? 0);
$card_number = trim($_POST['card_number'] ?? '');
$card_name   = trim($_POST['card_name'] ?? '');
$exp_month   = trim($_POST['exp_month'] ?? '');
$exp_year    = trim($_POST['exp_year'] ?? '');
$cvv         = trim($_POST['cvv'] ?? '');

// Données de test acceptées
$valid_number = '5555 1234 5678 9000';
$valid_cvv    = '555';

// Vérification des coordonnées bancaires
$valid = ($card_number === $valid_number && $cvv === $valid_cvv);

// Fichier de traçabilité
define('PAYMENTS_FILE', __DIR__ . '/../info/payments.json');
$payments = [];
if (file_exists(PAYMENTS_FILE)) {
    $payments = json_decode(file_get_contents(PAYMENTS_FILE), true) ?: [];
}

// Enregistrement de la transaction
$payments[] = [
    'session_id' => session_id(),
    'user'       => $_SESSION['user']['login'] ?? null,
    'voyage_id'  => $id,
    'card_last4' => substr(str_replace(' ', '', $card_number), -4),
    'holder'     => $card_name,
    'exp'        => $exp_month . '/' . $exp_year,
    'date'       => date('c'),
    'status'     => $valid ? 'accepted' : 'declined'
];
file_put_contents(PAYMENTS_FILE, json_encode($payments, JSON_PRETTY_PRINT));

// Redirection en fonction du résultat
if ($valid) {
    header('Location: confirmation.php?id=' . $id);
    exit;
} else {
    header('Location: erreur_paiement.php?id=' . $id);
    exit;
}
?>

