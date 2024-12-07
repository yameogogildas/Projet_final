<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../Views/login.php');
    exit();
}

// Récupérer tous les utilisateurs
$stmt = $pdo->prepare("SELECT id, username, role FROM users");
$stmt->execute();
$users = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
            font-family: 'Arial', sans-serif;
        }
        .admin-dashboard {
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }
        .table th {
            background-color: #007bff;
            color: white;
        }
        .table td {
            background-color: #f9f9f9;
        }
        .action-buttons a {
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
            margin-right: 10px;
            transition: all 0.3s ease;
        }
        .action-buttons a.modify {
            background-color: #4CAF50;
            color: white;
        }
        .action-buttons a.delete {
            background-color: #f44336;
            color: white;
        }
    </style>
</head>
<body>

<!-- Lien vers la page d'accueil -->
<a href="../Views/index.php" class="home-link">
    Accueil
</a>

<div class="container">
    <div class="admin-dashboard">
        <h1 class="my-4 text-center">Gérer les Utilisateurs</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID Utilisateur</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td class="action-buttons">
                            <a href="../Controllers/edit_user.php?id=<?= $user['id'] ?>" class="btn btn-success">Modifier</a>
                            <a href="../Controllers/delete_user.php?id=<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
