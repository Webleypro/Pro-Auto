<?php
session_start();

// Vérification de l'authentification
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $password = $_POST['password'];
    if ($id === 'admin' && $password === 'admin') {
        $_SESSION['loggedin'] = true;
    } else {
        $error = "Identifiants incorrects.";
    }
}

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['loggedin'])) {
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: #161616;
            color: #0052e9;
            margin: 0;
            padding: 20px;
        }

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #161616, #0052e9);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            z-index: -1;
        }

        @keyframes gradientAnimation {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .login-container {
            background: rgba(40, 39, 44, 0.8);
            border-radius: 20px;
            padding: clamp(20px, 5vw, 40px);
            width: 100%;
            max-width: 400px;
            box-shadow: 0 8px 32px rgba(255, 172, 237, 0.3);
        }

        .logo-container {
            text-align: center;
            margin-bottom: clamp(15px, 4vw, 30px);
        }

        #logo {
            max-width: min(150px, 80%);
            height: auto;
        }

        h2 {
            color: #0052e9;
            text-align: center;
            margin-bottom: clamp(20px, 5vw, 30px);
            font-weight: 600;
            font-size: clamp(1.5rem, 4vw, 2rem);
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            margin: 8px 0;
            padding: clamp(12px, 3vw, 15px);
            border: 1px solid #0052e9;
            border-radius: 10px;
            font-size: clamp(14px, 2vw, 16px);
            background: #161616;
            color: #0052e9;
            transition: all 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #0052e9;
            box-shadow: 0 0 10px rgba(255, 172, 237, 0.2);
        }

        .login-btn {
            width: 100%;
            padding: clamp(12px, 3vw, 15px);
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            font-size: clamp(14px, 2vw, 16px);
            background: #0052e9;
            color: #161616;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.3s ease;
        }

        .login-btn:hover {
            transform: translateY(-2px);
        }

        .error {
            color: #F44336;
            font-size: clamp(12px, 1.5vw, 14px);
            margin-top: 5px;
            text-align: left;
        }

        @media screen and (max-width: 480px) {
            .login-container {
                margin: 0 7px;
                padding: 20px;
                border-radius: 15px;
                width: calc(100% - 14px);
            }
        }
    </style>
</head>
<body>
    <div class="background-animation"></div>
    <div class="login-container">
        <div class="logo-container">
            <img src="http://s818987091.onlinehome.fr/kafika/pages/assets/img/logobloomy.png" alt="Logo" id="logo">
        </div>
        <h2>Réservation</h2>
        <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>
        <form method="post">
            <input type="text" name="id" placeholder="Identifiant" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit" class="login-btn">Se connecter</button>
        </form>
    </div>
</body>
</html>

<?php
exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration des Réservations</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #161616;
            color: #0052e9;
            margin: 0;
            padding: 20px;
        }

        .background-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #161616, #0052e9);
            background-size: 400% 400%;
            animation: gradientAnimation 15s ease infinite;
            z-index: -1;
        }

        .container {
            position: relative;
            max-width: 1200px;
            margin: 0 auto;
            background: rgba(40, 39, 44, 0.8);
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(255, 172, 237, 0.3);
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #0052e9;
            color: #161616;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 600;
            transition: transform 0.3s ease;
            text-decoration: none;
        }

        .logout-btn:hover {
            transform: translateY(-2px);
        }

        .reservations-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .reservations-table th,
        .reservations-table td {
            padding: 12px;
            border: 1px solid #0052e9;
            text-align: left;
        }

        .reservations-table th {
            background-color: #0052e9;
            color: #161616;
        }

        .status {
            padding: 5px 10px;
            border-radius: 4px;
            font-weight: bold;
        }

        .status-active {
            background-color: #4CAF50;
            color: white;
        }

        .status-modifiée {
            background-color: #FFC107;
            color: black;
        }

        .status-annulée {
            background-color: #F44336;
            color: white;
        }

        .delete-btn {
            background-color: #F44336;
            color: white;
            border: none;
            padding: 6px 10px;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-weight: bold;
        }

        .delete-btn:hover {
            background-color: #D32F2F;
        }

        .title {
            color: #0052e9;
            text-align: center;
            margin-bottom: 30px;
        }

        #logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .logo-container {
            text-align: center;
        }

        /* Modal Styles */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(40, 39, 44, 0.9);
            z-index: 1000;
            animation: fadeIn 0.3s ease;
        }

        .modal-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0.7);
            background: #161616;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 8px 32px rgba(255, 172, 237, 0.3);
            z-index: 1001;
            max-width: 90%;
            width: 400px;
            opacity: 0;
            animation: modalIn 0.5s ease forwards;
            border: 1px solid rgba(255, 172, 237, 0.2);
        }

        .modal-container.show {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }

        .modal-header {
            color: #0052e9;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.5rem;
            font-weight: 600;
        }

        .modal-content {
            color: #0052e9;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1rem;
            line-height: 1.5;
        }

        .modal-buttons {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .modal-btn {
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 1rem;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
        }

        .confirm-btn {
            background: #F44336;
            color: white;
        }

        .confirm-btn:hover {
            background: #D32F2F;
        }

        .cancel-btn {
            background: #0052e9;
            color: #161616;
        }

        .cancel-btn:hover {
            background: #ffc4e6;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes modalIn {
            0% {
                opacity: 0;
                transform: translate(-50%, -50%) scale(0.7);
            }
            100% {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
        }

        @keyframes shake {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            25% { transform: translate(-50%, -50%) rotate(-1deg); }
            75% { transform: translate(-50%, -50%) rotate(1deg); }
        }

        .shake {
            animation: shake 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="background-animation"></div>
    <div class="container">
        <a href="logout.php" class="logout-btn">Déconnexion</a>
        <div class="logo-container">
            <img src="http://s818987091.onlinehome.fr/kafika/pages/assets/img/logobloomy.png" alt="Logo" id="logo">
            <h1 class="title">Gestion des Réservations</h1>
        </div>
        <div id="reservations-container"></div>
    </div>

    <!-- Modal de suppression -->
    <div id="deleteModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">Confirmation de suppression</div>
            <div class="modal-content">
                Êtes-vous sûr de vouloir supprimer cette réservation ?
                <br>
                Cette action est irréversible.
            </div>
            <div class="modal-buttons">
                <button class="modal-btn cancel-btn" onclick="closeDeleteModal()">Annuler</button>
                <button class="modal-btn confirm-btn" onclick="confirmDelete()">Supprimer</button>
            </div>
        </div>
    </div>

    <audio id="notificationSound" src="notification.wav" preload="auto"></audio>
    <audio id="modificationSound" src="modifier.mp3" preload="auto"></audio>

    <script>
        let currentReservationId = null;

        function loadReservations() {
            fetch('get_reservations.php')
                .then(response => response.json())
                .then(data => {
                    const container = document.getElementById('reservations-container');
                    let html = '<table class="reservations-table">';
                    html += `<tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Message</th>
                        <th>Heure de l'envoie</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>`;

                    data.forEach(reservation => {
                        html += `<tr>
                            <td>${reservation.date}</td>
                            <td>${reservation.horaire}</td>
                            <td>${reservation.nom}</td>
                            <td>${reservation.telephone}</td>
                            <td>${reservation.email}</td>
                            <td>${reservation.total_guests}</td>
                            <td>${reservation.message}</td>
                            <td><span class="status status-${reservation.status.toLowerCase()}">${reservation.status}</span></td>
                            <td>
                                <button class="delete-btn" onclick="deleteReservation('${reservation.id}')">Supprimer</button>
                                </td>
                        </tr>`;
                    });

                    html += '</table>';
                    container.innerHTML = html;
                });
        }

        function showDeleteModal(id) {
            currentReservationId = id;
            const modal = document.getElementById('deleteModal');
            const modalContainer = modal.querySelector('.modal-container');
            modal.style.display = 'block';
            setTimeout(() => {
                modalContainer.classList.add('show');
            }, 10);
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            const modalContainer = modal.querySelector('.modal-container');
            modalContainer.classList.remove('show');
            setTimeout(() => {
                modal.style.display = 'none';
                currentReservationId = null;
            }, 300);
        }

        function confirmDelete() {
            if (currentReservationId) {
                fetch('delete_reservation.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ id: currentReservationId })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        closeDeleteModal();
                        loadReservations();
                    } else {
                        const modalContainer = document.querySelector('.modal-container');
                        modalContainer.classList.add('shake');
                        setTimeout(() => {
                            modalContainer.classList.remove('shake');
                        }, 500);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    const modalContainer = document.querySelector('.modal-container');
                    modalContainer.classList.add('shake');
                    setTimeout(() => {
                        modalContainer.classList.remove('shake');
                    }, 500);
                });
            }
        }

        function deleteReservation(id) {
            showDeleteModal(id);
        }

        // Chargement initial et rafraîchissement périodique
        loadReservations();
        setInterval(loadReservations, 1000);

        // Configuration WebSocket
        const ws = new WebSocket('wss://access818987092.webspace-data.io:PORT');

        ws.onmessage = function(event) {
            const data = JSON.parse(event.data);
            if (data.type === 'new_reservation') {
                document.getElementById('notificationSound').play();
                loadReservations();
            } else if (data.type === 'modification') {
                document.getElementById('modificationSound').play();
                loadReservations();
            }
        };

        // Gestion des erreurs WebSocket
        ws.onerror = function(error) {
            console.error('WebSocket Error:', error);
        };

        ws.onclose = function() {
            console.log('WebSocket Connection Closed');
            // Tentative de reconnexion après 5 secondes
            setTimeout(() => {
                window.location.reload();
            }, 5000);
        };

        // Fermeture de la modale si on clique en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('deleteModal');
            if (event.target === modal) {
                closeDeleteModal();
            }
        };
    </script>
</body>
</html>
