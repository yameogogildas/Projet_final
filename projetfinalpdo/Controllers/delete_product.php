<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=projetpdo', 'root', '');

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Vérifier si le produit est dans des commandes
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE product_id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $count = $stmt->fetchColumn();

    // Si le produit est trouvé dans des commandes, afficher un message d'erreur
    if ($count > 0) {
        echo "Ce produit ne peut pas être supprimé car il est déjà dans des commandes.";
    } else {
        // Si le produit n'est pas dans des commandes, supprimer le produit
        try {
            // Supprimer les éléments de la commande liés au produit (si vous ne voulez pas de suppression en cascade)
            $stmt_delete_order_items = $pdo->prepare("DELETE FROM order_items WHERE product_id = :product_id");
            $stmt_delete_order_items->execute(['product_id' => $product_id]);

            // Supprimer le produit de la table products
            $stmt_delete_product = $pdo->prepare("DELETE FROM products WHERE id = :product_id");
            $stmt_delete_product->execute(['product_id' => $product_id]);

            echo "Produit supprimé avec succès.";
        } catch (PDOException $e) {
            // En cas d'erreur, afficher le message d'erreur
            echo "Erreur : " . $e->getMessage();
        }
    }
} else {
    echo "Aucun produit trouvé.";
}
?>
