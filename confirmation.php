// confirmation.php
<?php
if (!isset($_GET['action'])) {
    header('Location: index.php');
    exit;
}

$action = $_GET['action'];
$message = '';
$title = '';

switch ($action) {
    case 'modified':
        $title = 'Réservation modifiée';
        $message = 'Votre réservation a été modifiée avec succès.';
        break;
    case 'cancelled':
        $title = 'Réservation annulée';
        $message = 'Votre réservation a été annulée avec succès.';
        break;
    default:
        header('Location: index.php');
        exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?= htmlspecialchars($title) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;400;600;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background-color: #242424;
            color: #c2ab70;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: #2a2a2a;
            border-radius: 10px;
            text-align: center;
            // confirmation.php (suite)
        }
        .message {
            margin: 20px 0;
            padding: 20px;
            background: #1a1a1a;
            border-radius: 5px;
        }
        .icon {
            font-size: 48px;
            margin-bottom: 20px;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px;
            background: #c2ab70;
            color: #242424;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">
            <?php if ($action === 'modified'): ?>
                ✏️
            <?php else: ?>
                ✖️
            <?php endif; ?>
        </div>
        <h2><?= htmlspecialchars($title) ?></h2>
        <div class="message">
            <p><?= htmlspecialchars($message) ?></p>
        </div>
        <a href="index.php" class="btn">Retour à l'accueil</a>
    </div>
</body>
</html>