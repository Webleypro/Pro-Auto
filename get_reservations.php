<?php
// get_reservations.php - Endpoint for fetching reservations
header('Content-Type: application/json');

function getReservations() {
    $reservations = [];
    $lines = file('reservations.txt', FILE_IGNORE_NEW_LINES);
    
    foreach ($lines as $line) {
        $data = explode('|', $line);
        $reservations[] = [
            'id' => $data[0],
            'status' => $data[1],
            'nom' => $data[2],
            'telephone' => $data[3],
            'email' => $data[4],
            'date' => $data[5],
            'horaire' => $data[6],
            'total_guests' => $data[7],
            'message' => $data[8] ?? '',
            'timestamp' => $data[9] ?? ''
        ];
    }
    
    // Sort by date and time
    usort($reservations, function($a, $b) {
        $dateA = strtotime($a['date'] . ' ' . $a['horaire']);
        $dateB = strtotime($b['date'] . ' ' . $b['horaire']);
        return $dateB - $dateA;
    });
    
    return $reservations;
}

echo json_encode(getReservations());
?>