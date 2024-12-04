<?php
// reservation.php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function genererEmailHTML($data, $id, $type = 'reservation') {
    $titre = "Confirmation de réservation de table";
    
    return "
    <html>
    <head>
        <title style= '@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');' 'font-family: 'Poppins', sans-serif;'>{$titre}</title>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap' rel='stylesheet'>
        <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap');

            body {
                font-family: 'Poppins', sans-serif;
                background-color: #28272c;
                color: #ffaced;
                padding: 20px;
                margin: 0;
            }
            .container {
                background-color: #28272c;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
                max-width: 600px;
                margin: auto;
                text-align: center;
            }
            h2 {
                color: #ffaced;
                margin-bottom: 20px;
                font-size: 28px;
            }
            p {
                margin: 10px 0;
                font-size: 16px;
            }
            .highlight {
                color: #ffaced;
                font-weight: bold;
            }
            .footer {
                margin-top: 20px;
                font-size: 14px;
                color: #ffaced;
                text-align: center;
                border-top: 1px solid #ffaced;
                padding-top: 15px;
            }
            .btn {
                background-color: #ffaced;
                color: #28272c;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 5px;
                margin: 10px;
                display: inline-block;
                border: none;
                transition: 0.5s ease;
            }
            .btn:hover {
                background-color: #de99cf;
            }
        </style>
    </head>
    <body>
        <div class='container'>
            <img src='http://s818987091.onlinehome.fr/kafika/pages/assets/img/logobloomy.png' alt='Logo' style='width: 280px; margin-bottom: 20px;'>
            <h2>{$titre}</h2>
            <p style='color: #ffaced;'><span class='highlight'>Nom :</span> {$data['nom']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Téléphone :</span> {$data['telephone']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Email :</span> {$data['email']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Date :</span> {$data['date']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Horaire :</span> {$data['horaire']}</p>
            " . 
            (!empty($data['message']) ? "<p style='color: #ffaced;'><span class='highlight'>Message :</span> {$data['message']}</p>" : "") 
            . "
            <p style='color: #ffaced;'>Votre réservation a été confirmée avec succès.</p>
            <p style='color: #ffaced;'>Numéro de réservation : <span class='highlight'>$id</span></p>
            
            <a href='https://www.google.com/maps/place/32+Rue+Pêcherie,+Tarare' class='btn' style='color: #28272c;'>Voir l'itinéraire</a>
            <a href='http://s818987091.onlinehome.fr/kafika/pages/modifier.php?id={$id}' class='btn' style='color: #28272c;'>Modifier ma réservation</a>
            <a href='http://s818987091.onlinehome.fr/kafika/pages/annuler.php?id={$id}' class='btn' style='color: #28272c;'>Annuler ma réservation</a>
            
            <div class='footer'>
                Nous vous attendons dans notre restaurant.<br>
                Adresse : <strong>32 Rue Pêcherie, Tarare</strong>
            </div>
        </div>
    </body>
    </html>";
}

function sauvegarderReservation($data) {
    $id = uniqid();
    $reservation_data = implode("|", [
        $id,
        'active',
        $data['nom'],
        $data['telephone'],
        $data['email'],
        $data['date'],
        $data['horaire'],
        $data['message'] ?? '',
        date('Y-m-d H:i:s')
    ]) . "\n";
    
    file_put_contents('reservations.txt', $reservation_data, FILE_APPEND);
    return $id;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'nom' => $_POST['nom'],
        'telephone' => $_POST['telephone'],
        'email' => $_POST['email'],
        'date' => $_POST['date'],
        'horaire' => $_POST['horaire'],
        'message' => $_POST['message'] ?? ''
    ];

    $reservation_id = sauvegarderReservation($data);

    // Envoyer l'email de confirmation
    $to = $data['email'];
    $cc = "elammariachraf351@gmail.com";
    $subject = "Confirmation de réservation de table";
    $message_html = genererEmailHTML($data, $reservation_id);
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Bloomy Coffee Restaurant <sh-818987092@eu.hosting-webspace.io>" . "\r\n";
    $headers .= "Cc: $cc" . "\r\n";
    
    // Envoi de l'email
    if (mail($to, $subject, $message_html, $headers)) {
        echo "<p style='color:green;'>Réservation de table confirmée. Veuillez consulter votre boîte mail.</p>";
    } else {
        echo "<p style='color:red;'>Erreur lors de l'envoi du mail. Veuillez réessayer.</p>";
    }
}

// Retourner le message JSON si c'est une requête AJAX
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    echo json_encode(['message' => $message]);
    exit;
}
?>
