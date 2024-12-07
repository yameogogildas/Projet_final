<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../Views/login.php');
    exit();
}

if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Récupérer les informations de l'utilisateur à modifier
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();

    if (!$user) {
        echo "Utilisateur non trouvé.";
        exit();
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = $_POST['username'];
        $role = $_POST['role'];

        // Mettre à jour les informations de l'utilisateur
        $stmt = $pdo->prepare("UPDATE users SET username = ?, role = ? WHERE id = ?");
        if ($stmt->execute([$username, $role, $user_id])) {
            header('Location: ../Views/admin_users.php');
            exit();
        } else {
            echo "Erreur lors de la mise à jour.";
        }
    }
} else {
    echo "ID utilisateur non spécifié.";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier l'utilisateur</title>
</head>
<body>
    <h1>Modifier l'utilisateur</h1>
    <form method="post">
        <div>
            <label for="username">Nom d'utilisateur</label>
            <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
        </div>
        <div>
            <label for="role">Rôle</label>
            <select name="role" required>
                <option value="user" <?= $user['role'] == 'user' ? 'selected' : '' ?>>Utilisateur</option>
                <option value="admin" <?= $user['role'] == 'admin' ? 'selected' : '' ?>>Administrateur</option>
            </select>
        </div>
        <div>
            <button type="submit">Mettre à jour</button>
        </div>
    </form>
</body>
</html>
