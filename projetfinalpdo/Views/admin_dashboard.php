<?php
session_start();
include('../Models/db.php');

// Vérifier si l'utilisateur est administrateur
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header('Location: ../Views/login.php');
    exit();
}

// Définir la section à afficher
$section = isset($_GET['section']) ? $_GET['section'] : 'products';

// Récupérer les commandes
if ($section == 'orders') {
    $stmt = $pdo->prepare("SELECT id, user_id, total, status, shipping_address, first_name, last_name, phone_number FROM orders ORDER BY created_at DESC");
    $stmt->execute();
    $orders = $stmt->fetchAll();
}

// Récupérer les produits
if ($section == 'products') {
    $stmt_products = $pdo->prepare("SELECT * FROM products");
    $stmt_products->execute();
    $products = $stmt_products->fetchAll();
}

// Récupérer les utilisateurs
if ($section == 'users') {
    $stmt_users = $pdo->prepare("SELECT id, username, role FROM users");
    $stmt_users->execute();
    $users = $stmt_users->fetchAll();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Administrateur</title>
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

        .btn {
            padding: 12px 24px;
            font-size: 16px;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
        }

        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
        }

        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 123, 255, 0.2);
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
        <h1 class="my-4 text-center">Tableau de bord Administrateur</h1>

        <!-- Liens pour gérer les produits, commandes et utilisateurs -->
        <div class="text-center mb-4">
            <a href="../Views/admin_dashboard.php?section=products" class="btn btn-primary">Gérer les produits</a>
            <a href="../Views/admin_dashboard.php?section=orders" class="btn btn-primary">Gérer les commandes</a>
            <a href="../Views/admin_dashboard.php?section=users" class="btn btn-primary">Gérer les utilisateurs</a>
        </div>

        <!-- Gestion des utilisateurs -->
        <?php if ($section == 'users'): ?>
            <h2>Liste des Utilisateurs</h2>
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
        <?php endif; ?>

        <!-- Gestion des produits -->
        <?php if ($section == 'products'): ?>
            <h2>Liste des Produits</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Prix</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?= htmlspecialchars($product['name']) ?></td>
                            <td><?= htmlspecialchars($product['description']) ?></td>
                            <td><?= number_format($product['price'], 2) ?> €</td>
                            <td><img src="../images/<?= htmlspecialchars($product['image']) ?>" alt="<?= htmlspecialchars($product['name']) ?>" width="100"></td>
                            <td class="action-buttons">
                                <a href="../Controllers/edit_product.php?id=<?= $product['id'] ?>" class="btn btn-success">Modifier</a>
                                <a href="../Controllers/delete_product.php?id=<?= $product['id'] ?>" class="btn btn-danger">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Gestion des commandes -->
        <?php if ($section == 'orders'): ?>
            <h2>Liste des Commandes</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Commande</th>
                        <th>Utilisateur ID</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Numéro de téléphone</th>
                        <th>Total</th>
                        <th>Adresse</th>
                        <th>Status</th>
                        <th>Produits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order): ?>
                        <tr>
                            <td><?= htmlspecialchars($order['id']) ?></td>
                            <td><?= htmlspecialchars($order['user_id']) ?></td>
                            <td><?= htmlspecialchars($order['last_name']) ?></td>
                            <td><?= htmlspecialchars($order['first_name']) ?></td>
                            <td><?= htmlspecialchars($order['phone_number']) ?></td>
                            <td><?= number_format($order['total'], 2) ?> €</td>
                            <td><?= htmlspecialchars($order['shipping_address']) ?></td>
                            <td><?= htmlspecialchars($order['status']) ?></td>
                            <td>
                                <?php
                                // Récupérer les produits associés à la commande
                                $stmt_items = $pdo->prepare("SELECT p.name, oi.quantity, oi.price FROM order_items oi JOIN products p ON oi.product_id = p.id WHERE oi.order_id = ?");
                                $stmt_items->execute([$order['id']]);
                                $items = $stmt_items->fetchAll();
                                ?>
                                <ul>
                                    <?php foreach ($items as $item): ?>
                                        <li><?= htmlspecialchars($item['name']) ?> - <?= $item['quantity'] ?> x <?= number_format($item['price'], 2) ?> €</li>
                                    <?php endforeach; ?>
                                </ul>
                            </td>
                            <td class="action-buttons">
                                <!-- Formulaire pour supprimer la commande -->
                                <form action="../Controllers/delete_order.php" method="post" style="display:inline;">
                                    <input type="hidden" name="order_id" value="<?= $order['id'] ?>">
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?');">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
