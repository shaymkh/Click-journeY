<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user']['login'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit;
}

$login = $_SESSION['user']['login'];
$chemin = __DIR__ . '/users.json';
$data = file_exists($chemin) ? json_decode(file_get_contents($chemin), true) : [];

foreach ($data as &$user) {
    if ($user['login'] === $login) {
        $user['prenom'] = $_POST['prenom'] ?? $user['prenom'];
        $user['nom']    = $_POST['nom'] ?? $user['nom'];
        $user['email']  = $_POST['email'] ?? $user['email'];
        $_SESSION['user'] = $user;
        file_put_contents($chemin, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès.']);
        exit;
    }
}

echo json_encode(['success' => false, 'message' => 'Utilisateur introuvable.']);
