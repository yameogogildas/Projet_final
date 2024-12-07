<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['username'])) {
    header('Location: ../Views/login.php');
    exit();
}

// Récupérer tous les produits depuis la base de données
$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Produits</title>
    <!-- Inclure Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding-top: 60px; /* Espace pour la barre de navigation */
            background-color: #f8f9fa;
        }
        /* Style de la barre de navigation horizontale */
        .navbar {
            width: 100%;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 10px 0;
            z-index: 1000;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 20px;
            display: flex;
            align-items: center;
            transition: background-color 0.3s ease;
        }
        .navbar a:hover {
            background-color: #495057;
        }
        .navbar a i {
            margin-right: 8px;
            font-size: 18px;
        }
        /* Style pour afficher le nombre de produits dans le panier collé à l'icône */
        .cart-count {
            background-color: red;
            border-radius: 50%;
            padding: 3px 8px;
            font-size: 14px;
            color: white;
            margin-left: 5px; /* Espace entre l'icône et le nombre */
        }
        /* Style pour la liste des produits */
        .product-container {
            padding: 20px;
            text-align: center;
            max-width: 1200px;
            margin: 0 auto;
        }
        .product-list {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .product {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
            margin: 10px;
            width: 200px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }
        .product img {
            width: 150px;
            height: 150px;
            object-fit: contain;
            border-radius: 8px;
        }
        .product h3 {
            margin: 15px 0;
            font-size: 18px;
            color: #333;
        }
        .product p {
            margin: 10px 0;
            color: #666;
        }
        .product button {
            background-color: #28a745;
            color: white;
            border: none;
            padding: 12px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .product button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>

    <!-- Barre de navigation horizontale -->
    <div class="navbar">
        <a href="../Views/index.php"><i class="fas fa-home"></i>Accueil</a>
        <a href="../Views/products.php"><i class="fas fa-box"></i>Produits</a>
        <!-- Panier : seulement l'icône et le nombre de produits dans le panier -->
        <a href="../Views/cart.php">
            <i class="fas fa-shopping-cart"></i>
            <span class="cart-count"><?= isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0 ?></span>
        </a>
        <a href="../Views/profile.php"><i class="fas fa-user"></i>Profil</a>
        <a href="../views/logout.php"><i class="fas fa-sign-out-alt"></i>Déconnexion</a>
    </div>

    <!-- Contenu principal -->
    <div class="product-container">
        <h1>Liste des Produits</h1>
        <div class="product-list">
            <?php foreach ($products as $product): ?>
                <div class="product">
                    <img src="../images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                    <h3><?= htmlspecialchars($product['name']) ?></h3>
                    <p><?= htmlspecialchars($product['description']) ?></p>
                    <p><strong>Prix: <?= number_format($product['price'], 2) ?> €</strong></p>
                    <form action="../Controllers/add_to_cart.php" method="POST">
                        <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                        <button type="submit">Ajouter au panier</button>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

</body>
</html>
