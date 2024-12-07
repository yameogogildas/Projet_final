<?php
session_start();
include('../Models/db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
        $_SESSION['user_id'] = $user['id'];
        if ($_SESSION['role'] == 'admin') {
            header('Location: admin_dashboard.php');
        } else {
            header('Location: products.php');
        }
    } else {
        echo "<div class='alert'>Identifiants invalides.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de connexion</title>

    <!-- Inclure Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(to right, #a4d3a9, #f1b7b7);
        }

        .container {
            text-align: center;
            background: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        .home-link {
            position: absolute;
            top: 10px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 24px;
            color: #007bff;
            text-decoration: none;
        }

        .home-link:hover {
            color: #0056b3;
        }

        h2 {
            color: #333;
            font-size: 2em;
            margin-bottom: 20px;
        }

        form input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 1em;
        }

        form button {
            width: 100%;
            padding: 12px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
            transition: background-color 0.3s;
        }

        form button:hover {
            background-color: #218838;
        }

        .signup-message {
            margin-top: 20px;
            font-size: 1.2em;
            color: #666;
        }

        .signup-message a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .signup-message a:hover {
            text-decoration: underline;
        }

        .alert {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            margin-top: 15px;
            border-radius: 4px;
            font-weight: bold;
        }

        /* Animation message */
        .alert {
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            0% { opacity: 0; }
            100% { opacity: 1; }
        }
    </style>
</head>
<body>

    <!-- Icône "Home" avec chapeau de Noël en haut au centre -->
    <a href="index.php" class="home-link">
        <i class="fas fa-home"></i><i class="fas fa-candy-cane"></i> Accueil
    </a>

    <div class="container">
        <h2>Connexion</h2>
        <form method="post">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>

        <!-- Message pour inviter à créer un compte -->
        <div class="signup-message">
            <p>Pas encore de compte ? <a href="register.php">Créez-en un maintenant !</a></p>
        </div>
    </div>

</body>
</html>
