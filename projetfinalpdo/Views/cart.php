<?php
session_start();
include('../Models/db.php'); // Inclusion de votre fichier de connexion à la base de données

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: ../Views/login.php'); // Redirection si l'utilisateur n'est pas connecté
    exit();
}

// Initialiser le total du panier et les éléments du panier
$total = 0;
$cart_items = [];

// Vérifier si le panier contient des produits
if (isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $product['price'] * $quantity
            ];
            $total += $product['price'] * $quantity;
        }
    }
} else {
    // Si le panier est vide, afficher un message
    echo "Votre panier est vide. Ajoutez des produits avant de passer commande.";
    exit();
}

// Gérer la soumission du formulaire de commande
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        $product_id = $_POST['product_id'];
        
        // Gérer les actions sur le panier
        switch ($_POST['action']) {
            case 'increase':
                // Augmenter la quantité du produit dans le panier
                $_SESSION['cart'][$product_id] += 1;
                break;
            
            case 'decrease':
                // Diminuer la quantité du produit dans le panier
                if ($_SESSION['cart'][$product_id] > 1) {
                    $_SESSION['cart'][$product_id] -= 1;
                }
                break;
            
            case 'remove':
                // Supprimer le produit du panier
                unset($_SESSION['cart'][$product_id]);
                break;
        }
        
        // Redirection pour actualiser la page
        header('Location: ../Views/cart.php');
        exit();
    }

    // Gérer la soumission du formulaire de commande
    if (isset($_POST['place_order'])) {
        // Récupérer les données du formulaire
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $phone_number = htmlspecialchars($_POST['phone_number']);
        $shipping_address = htmlspecialchars($_POST['shipping_address']);
        $user_id = $_SESSION['user_id']; // Assurez-vous que l'ID de l'utilisateur est stocké dans la session

        // Préparer la requête d'insertion de la commande dans la table "orders"
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, shipping_address, first_name, last_name, phone_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $total, 'pending', $shipping_address, $first_name, $last_name, $phone_number]);
        
        // Récupérer l'ID de la dernière commande insérée
        $order_id = $pdo->lastInsertId();
        
        // Insérer les produits associés à la commande dans la table "order_items"
        foreach ($cart_items as $item) {
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->execute([$order_id, $item['product']['id'], $item['quantity'], $item['product']['price']]);
        }
        
        // Vider le panier après la commande
        unset($_SESSION['cart']);
        
        // Rediriger l'utilisateur vers la page de confirmation de commande
        header('Location: ../Controllers/order_confirmation.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Panier</title>
    <!-- Intégration de Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJx3RQ3f1c2e3WX1apP9QgmIwiGs4Ff3e7baB10P99w1ZRf8t4eUKY1v8PqF" crossorigin="anonymous">
    
    <!-- Intégration du SDK PayPal -->
    <script src="https://www.paypal.com/sdk/js?client-id=sb&currency=EUR" data-namespace="paypal_sdk"></script> <!-- Utilisez la clé sandbox ici -->

    <style>
        /* Style général */
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding-top: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Style du panier */
        .cart-item {
            border: 1px solid #ccc;
            padding: 20px;
            margin: 10px 0;
            display: flex;
            align-items: center;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            margin-right: 20px;
            border-radius: 8px;
        }
        .cart-item .details {
            flex-grow: 1;
        }
        .cart-item .controls {
            margin-left: 20px;
            text-align: center;
        }
        
        /* Boutons */
        .btn-primary, .btn-warning, .btn-danger {
            border-radius: 5px;
            font-size: 14px;
            padding: 5px 15px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .btn-warning {
            background-color: #ffc107;
            border-color: #ffc107;
        }
        .btn-warning:hover {
            background-color: #e0a800;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-danger:hover {
            background-color: #c82333;
        }

        /* Section de total et checkout */
        .total {
            margin-top: 20px;
            font-size: 1.2em;
        }
        .checkout {
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-size: 16px;
        }
        .checkout:hover {
            background-color: #218838;
        }

        /* Formulaire de commande */
        .checkout-form input,
        .checkout-form textarea {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        .checkout-form button {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .checkout-form button:hover {
            background-color: #218838;
        }
        
        /* PayPal Button Container */
        #paypal-button-container {
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="my-4">Mon Panier</h1>

    <?php if (count($cart_items) > 0): ?>
        <div id="cart-items">
            <?php foreach ($cart_items as $item): ?>
                <div class="cart-item row">
                    <div class="col-md-3">
                        <img src="../images/<?= htmlspecialchars($item['product']['image']) ?>" alt="<?= htmlspecialchars($item['product']['name']) ?>">
                    </div>
                    <div class="col-md-6 details">
                        <h3><?= htmlspecialchars($item['product']['name']) ?></h3>
                        <p><?= htmlspecialchars($item['product']['description']) ?></p>
                        <p><strong>Prix unitaire: <?= number_format($item['product']['price'], 2) ?> €</strong></p>
                        <p><strong>Quantité: <?= $item['quantity'] ?></strong></p>
                    </div>
                    <div class="col-md-3 controls">
                        <form action="cart.php" method="POST">
                            <input type="hidden" name="product_id" value="<?= $item['product']['id'] ?>">
                            <button type="submit" name="action" value="increase" class="btn btn-warning">Augmenter</button><br><br>
                            <button type="submit" name="action" value="decrease" class="btn btn-warning">Diminuer</button><br><br>
                            <button type="submit" name="action" value="remove" class="btn btn-danger">Retirer</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="total">
            <p>Total: <?= number_format($total, 2) ?> €</p>
        </div>

        <!-- Formulaire de commande -->
        <form action="cart.php" method="POST" class="checkout-form">
            <h3>Informations de Livraison</h3>
            <input type="text" name="first_name" placeholder="Prénom" required>
            <input type="text" name="last_name" placeholder="Nom" required>
            <input type="text" name="phone_number" placeholder="Numéro de téléphone" required>
            <textarea name="shipping_address" placeholder="Adresse de livraison" required></textarea>
            <button type="submit" name="place_order">Passer la commande</button>
        </form>

        <!-- PayPal Button -->
        <div id="paypal-button-container"></div>

        <script>
            paypal_sdk.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?= number_format($total, 2) ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions) {
                    return actions.order.capture().then(function(details) {
                        window.location.href = '../Controllers/order_confirmation.php'; // Redirection vers confirmation après paiement
                    });
                }
            }).render('#paypal-button-container');
        </script>

    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0F6+Af1X7du6b1wYkT2v10T9mHzoqXr3duNc4eXkZXr1+Z7Zg" crossorigin="anonymous"></script>

</body>
</html>
