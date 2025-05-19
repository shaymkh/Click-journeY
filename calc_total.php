<?php
header('Content-Type: application/json');

// Pause de 2 secondes pour simuler la latence
sleep(2);

$data = json_decode(file_get_contents('php://input'), true);
$id = intval($data['id'] ?? 0);
$options = $data['options'] ?? [];
$nb = max(1, intval($data['nb_personnes'] ?? 1));

$voyages = json_decode(@file_get_contents(__DIR__ . '/voyages.json'), true) ?: [];

foreach ($voyages as $v) {
    if ($v['id'] === $id) {
        $total = floatval($v['prix_base']);

        foreach ($v['etapes'] as $i => $etape) {
            foreach ($etape['options'] as $optName => $vals) {
                $choix = $options[$i][$optName] ?? $etape['options_defaut'][$optName] ?? null;
                if ($choix !== null && isset($vals[$choix])) {
                    $total += floatval($vals[$choix]);
                }
            }
        }

        echo json_encode([
            'total' => number_format($total * $nb, 2, ',', ' ')
        ]);
        exit;
    }
}

// Si aucun voyage trouvé
http_response_code(404);
echo json_encode(['error' => 'Voyage introuvable']);
exit;
