<?php
header('Content-Type: application/json');

$id = intval($_GET['id'] ?? 0);
$chemin = __DIR__ . '/voyages.json';

$data = @file_get_contents($chemin);
$voyages = $data ? json_decode($data, true) : [];

foreach ($voyages as $v) {
    if ($v['id'] == $id) {
        echo json_encode([
            'titre' => $v['titre'],
            'etapes' => $v['etapes'],
        ]);
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Voyage introuvable']);
