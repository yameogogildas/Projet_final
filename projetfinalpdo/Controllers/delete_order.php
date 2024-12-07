<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est un administrateur
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../Views/login.php');
    exit();
}

// Vérifier si l'ID de la commande est bien envoyé
if (isset($_POST['order_id'])) {
    $order_id = $_POST['order_id'];

    // Commencer une transaction pour garantir que les deux suppressions se produisent ensemble
    try {
        $pdo->beginTransaction();

        // Suppression des produits associés à la commande
        $stmt_items = $pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt_items->execute([$order_id]);

        // Suppression de la commande dans la table "orders"
        $stmt = $pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$order_id]);

        // Valider la transaction
        $pdo->commit();

        // Rediriger l'administrateur vers le tableau de bord des commandes
        header('Location: ../Views/admin_dashboard.php?section=orders');
        exit();
    } catch (Exception $e) {
        // En cas d'erreur, annuler la transaction
        $pdo->rollBack();
        echo "Erreur lors de la suppression de la commande: " . $e->getMessage();
    }
} else {
    echo "Erreur : l'ID de la commande n'a pas été envoyé.";
}
?>
