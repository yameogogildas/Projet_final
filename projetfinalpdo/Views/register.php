<?php
session_start();
include('../Views/db.php');

// Variable pour afficher un message après l'inscription
$success_message = '';

if (isset($_SESSION['username'])) {
    // Si l'utilisateur est déjà connecté, ne rien faire (ou rediriger si nécessaire)
    $success_message = "Vous êtes déjà connecté.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash du mot de passe
    $role = 'user';  // Attribuer le rôle "user" par défaut

    // Vérification si le nom d'utilisateur est déjà pris
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if ($stmt->fetchColumn() > 0) {
        $success_message = "Ce nom d'utilisateur est déjà pris.";
    } else {
        // Insérer l'utilisateur dans la base de données
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        if ($stmt->execute([$username, $password, $role])) {
            // Inscription réussie, mais ne pas rediriger, juste afficher un message de succès
            $success_message = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
        } else {
            $success_message = "Erreur lors de l'inscription. Veuillez réessayer.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>

    <!-- Ajouter le style pour rendre la page plus attrayante -->
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

        h1 {
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

    <div class="container">
        <h1>Inscription</h1>

        <!-- Afficher le message de succès ou d'erreur -->
        <?php if ($success_message): ?>
            <p class="alert"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <form method="post">
            <div>
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" name="username" id="username" required>
            </div>
            <div>
                <label for="password">Mot de passe :</label>
                <input type="password" name="password" id="password" required>
            </div>
            <div>
                <button type="submit">S'inscrire</button>
            </div>
        </form>

        <p class="signup-message">Vous avez déjà un compte ? <a href="../Views/login.php">Connectez-vous ici</a></p>
    </div>

</body>
</html>
