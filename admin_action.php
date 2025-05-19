<?php
session_start();
sleep(2); // Simule latence pour voir ⏳

header('Content-Type: application/json');

if ($_SESSION['user']['role'] !== 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Accès refusé']);
    exit;
}

$login = $_POST['login'] ?? '';
$type = $_POST['type'] ?? '';

if (!in_array($type, ['vip', 'banni'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Type invalide']);
    exit;
}

$chemin = __DIR__ . '/utilisateurs.json';
$data = json_decode(@file_get_contents($chemin), true) ?: [];

foreach ($data as &$user) {
    if ($user['login'] === $login) {
        $user[$type] = empty($user[$type]) ? true : false;
        file_put_contents($chemin, json_encode($data, JSON_PRETTY_PRINT));
        echo json_encode(['success' => true, 'newValue' => $user[$type]]);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Utilisateur introuvable']);
