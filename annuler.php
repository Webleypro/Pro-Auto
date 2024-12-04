<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

function cancelReservation($id) {
    $reservations = file('reservations.txt', FILE_IGNORE_NEW_LINES);
    $cancelled = false;
    $newContent = '';
    $reservationData = null;

    foreach ($reservations as $reservation) {
        $data = explode('|', $reservation);
        if ($data[0] === $id) {
            $data[1] = 'annulée';
            $reservationData = [
                'nom' => $data[2],
                'telephone' => $data[3],
                'email' => $data[4],
                'date' => $data[5],
                'horaire' => $data[6],
                'total_guests' => $data[7],
                'message' => $data[8] ?? ''
            ];
            $newContent .= implode('|', $data) . "\n";
            $cancelled = true;
        } else {
            $newContent .= $reservation . "\n";
        }
    }

    if ($cancelled) {
        file_put_contents('reservations.txt', $newContent);
        return $reservationData;
    }
    return null;
}

function genererEmailAnnulation($data, $id) {
    return "
    <html>
    <head>
        <title>Confirmation d'Annulation de votre Réservation</title>
        <link rel='preconnect' href='https://fonts.gstatic.com' crossorigin>
        <link href='https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap' rel='stylesheet'>
        <style>
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
            <h2>Confirmation d'Annulation</h2>
            <p style='color: #ffaced;'><span class='highlight'>Nom :</span> {$data['nom']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Téléphone :</span> {$data['telephone']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Email :</span> {$data['email']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Date :</span> {$data['date']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Horaire :</span> {$data['horaire']}</p>
            <p style='color: #ffaced;'><span class='highlight'>Nombre de convives :</span> {$data['total_guests']}</p>
            " . (!empty($data['message']) ? "<p style='color: #ffaced;'><span class='highlight'>Message :</span> {$data['message']}</p>" : "") . "
            <p style='color: #ffaced;'>Votre réservation a été annulée avec succès.</p>
            <p style='color: #ffaced;'>Numéro de réservation : <span class='highlight'>$id</span></p>
            
            <a href='https://www.google.com/maps/place/32+Rue+Pêcherie,+Tarare' class='btn' style='color: #28272c;'>Voir l'itinéraire</a>
            
            <div class='footer'>
                Nous espérons vous revoir bientôt dans notre restaurant.<br>
                Adresse : <strong>32 Rue Pêcherie, Tarare</strong>
            </div>
        </div>
    </body>
    </html>";
}

$message = '';

if (isset($_GET['id'])) {
    if (isset($_POST['confirm'])) {
        $reservationData = cancelReservation($_GET['id']);
        
        if ($reservationData) {
            $to = $reservationData['email'];
            $subject = "Confirmation d'annulation de votre réservation";
            $message_html = genererEmailAnnulation($reservationData, $_GET['id']);
            
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= "From: Bloomy Coffee Restaurant <sh-818987092@eu.hosting-webspace.io>" . "\r\n";
            
            if (mail($to, $subject, $message_html, $headers)) {
                $message = '<div style="text-align: center; margin-bottom: 20px;">Votre réservation a été annulée avec succès. Un email de confirmation vous a été envoyé.</div>';
            } else {
                $message = '<div style="text-align: center; margin-bottom: 20px;">Votre réservation a été annulée, mais l\'envoi de l\'email a échoué.</div>';
            }
        } else {
            $message = '<div style="text-align: center; margin-bottom: 20px;">Erreur lors de l\'annulation de la réservation.</div>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Annuler votre réservation - Bloomy Coffee</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #28272c;
            color: #ffaced;
            margin: 0;
            padding: 10px;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #28272c, #ffaced);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            z-index: -1;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            background: #28272c;
            border-radius: 20px;
            padding: 40px;
            width: 90%;
            max-width: 400px;
            box-shadow: 0 8px 32px #ffaced;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        #logo {
            max-width: 200px;
            height: auto;
        }

        h1 {
            font-size: 24px;
            color: #ffaced;
            text-align: center;
            margin-bottom: 30px;
        }

        .message {
            margin: 20px 0;
            padding: 15px;
            border-radius: 4px;
            text-align: center;
            color: #ffaced;
        }

        button {
            background-color: #ffaced;
            color: #28272c;
            border: none;
            padding: 15px 30px;
            border-radius: 10px;
            cursor: pointer;
            font-weight: bold;
            font-size: 16px;
            width: 100%;
            transition: background-color 0.3s, transform 0.2s;
            margin-top: 20px;
            font-family: 'Poppins', sans-serif;
        }

        button:hover {
            background-color: #de99cf;
            transform: translateY(-2px);
        }

        .btn {
            display: inline-block;
            background-color: #ffaced;
            color: #28272c;
            text-decoration: none;
            padding: 15px 30px;
            border-radius: 10px;
            font-weight: bold;
            margin-top: 20px;
            transition: background-color 0.3s, transform 0.2s;
            width: calc(100% - 60px);
            text-align: center;
        }

        .btn:hover {
            background-color: #de99cf;
            transform: translateY(-2px);
        }

        @media screen and (max-width: 480px) {
            .container {
                padding: 20px;
            }

            button, .btn {
                font-size: 14px;
                padding: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="background-animation"></div>
    <div class="container">
        <div class="logo-container">
            <img src="http://s818987091.onlinehome.fr/kafika/pages/assets/img/logobloomy.png" alt="Logo" id="logo">
        </div>
        
        <h1>Annuler votre réservation</h1>
        <?php echo $message; ?>
        
        <form method="post">
            <button type="submit" name="confirm">Confirmer l'annulation</button>
        </form>
        <a href="home-page-3.html" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>