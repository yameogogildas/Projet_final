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

    // Vérifier si l'utilisateur à supprimer existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() > 0) {
        // Supprimer l'utilisateur
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        if ($stmt->execute([$user_id])) {
            header('Location: ../Views/admin_users.php');
            exit();
        } else {
            echo "Erreur lors de la suppression de l'utilisateur.";
        }
    } else {
        echo "L'utilisateur spécifié n'existe pas.";
    }
} else {
    echo "ID utilisateur non fourni.";
}
?>
