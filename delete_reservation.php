<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Méthode non autorisée']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'] ?? null;

if (!$id) {
    echo json_encode(['success' => false, 'message' => 'ID manquant']);
    exit;
}

$reservations = file('reservations.txt', FILE_IGNORE_NEW_LINES);
$newContent = '';
$deleted = false;

foreach ($reservations as $reservation) {
    $data = explode('|', $reservation);
    if ($data[0] !== $id) {
        $newContent .= $reservation . "\n";
    } else {
        $deleted = true;
    }
}

if ($deleted) {
    file_put_contents('reservations.txt', $newContent);
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Réservation non trouvée']);
}
?>