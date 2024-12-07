<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: login.php"); // Rediriger vers la page de connexion si non connecté
    exit();
}

// Récupérer les informations de l'utilisateur à partir de la base de données
$username = $_SESSION['username'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

if ($user) {
    // Affichage des informations de l'utilisateur
    $user_role = $user['role'];
    $user_id = $user['id'];  // Supposons que l'utilisateur a un ID unique
} else {
    // Rediriger si l'utilisateur n'existe pas dans la base de données
    header("Location: ../Views/login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Utilisateur</title>

    <!-- Style pour la page de profil -->
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

        .profile-container {
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

        .profile-info {
            font-size: 1.2em;
            color: #333;
        }

        .profile-info p {
            margin: 10px 0;
        }

        .logout-btn {
            padding: 12px 20px;
            background-color: #dc3545;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1em;
        }

        .logout-btn:hover {
            background-color: #c82333;
        }
    </style>
</head>
<body>

    <div class="profile-container">
        <h1>Profil de l'utilisateur</h1>

        <div class="profile-info">
            <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Rôle :</strong> <?php echo htmlspecialchars($user_role); ?></p>
            <p><strong>ID :</strong> <?php echo htmlspecialchars($user_id); ?></p>
        </div>

        <!-- Bouton de déconnexion -->
        <form action="logout.php" method="post">
            <button type="submit" class="logout-btn">Se déconnecter</button>
        </form>
    </div>

</body>
</html>
