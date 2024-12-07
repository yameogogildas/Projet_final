<?php
session_start();

if (isset($_POST['product_id'], $_POST['action'])) {
    $product_id = $_POST['product_id'];
    $action = $_POST['action'];

    switch ($action) {
        case 'increase':
            $_SESSION['cart'][$product_id]++;
            break;
        case 'decrease':
            if ($_SESSION['cart'][$product_id] > 1) {
                $_SESSION['cart'][$product_id]--;
            }
            break;
        case 'remove':
            unset($_SESSION['cart'][$product_id]);
            break;
    }
}

header('Location: ../Views/cart.php');
?>
