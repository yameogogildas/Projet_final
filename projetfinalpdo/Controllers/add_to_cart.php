<?php
session_start();
include('../Models/db.php');

if (!isset($_SESSION['username'])) {
    header('Location: ../Views/login.php');
    exit();
}

$product_id = $_POST['product_id'];
$user_id = $_SESSION['user_id']; // ID de l'utilisateur

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if ($product) {
    // Vérifier si le produit est déjà dans le panier
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id]++;
    } else {
        $_SESSION['cart'][$product_id] = 1;
    }
}

header('Location: ../Views/products.php');
?>
