<?php
// Connexion à la base de données
$pdo = new PDO('mysql:host=localhost;dbname=projetpdo', 'root', '');

// Vérifier si un ID est passé dans l'URL
if (isset($_GET['id'])) {
    $product_id = $_GET['id'];

    // Récupérer les détails du produit
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :product_id");
    $stmt->execute(['product_id' => $product_id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si le produit n'existe pas
    if (!$product) {
        echo "Produit non trouvé.";
        exit;
    }
} else {
    echo "Aucun produit trouvé.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $image = $_FILES['image'];

    // Vérifier si le dossier 'images' existe
    $uploadDir = 'images/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);  // Créer le dossier si nécessaire
    }

    // Gestion de l'upload de l'image
    if ($image['error'] == 0) {
        // Vérifier si le fichier est une image
        $image_extension = pathinfo($image['name'], PATHINFO_EXTENSION);
        $allowed_extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array(strtolower($image_extension), $allowed_extensions)) {
            // Générer un nom unique pour l'image
            $image_name = uniqid('product_') . '.' . $image_extension;
            $image_path = $uploadDir . $image_name;

            // Déplacer l'image téléchargée vers le dossier approprié
            if (move_uploaded_file($image['tmp_name'], $image_path)) {
                // Supprimer l'ancienne image (si elle existe)
                if (file_exists($product['image'])) {
                    unlink($product['image']);
                }
            } else {
                echo "Erreur lors de l'upload de l'image.";
                exit;
            }
        } else {
            echo "L'image doit être au format JPG, JPEG, PNG ou GIF.";
            exit;
        }
    } else {
        // Si aucune nouvelle image n'est téléchargée, garder l'image actuelle
        $image_path = $product['image'];
    }

    // Mettre à jour les informations du produit dans la base de données
    $stmt = $pdo->prepare("UPDATE products SET name = :name, description = :description, price = :price, image = :image WHERE id = :product_id");
    $stmt->execute([
        'name' => $name,
        'description' => $description,
        'price' => $price,
        'image' => $image_path,
        'product_id' => $product_id
    ]);

    echo "Produit mis à jour avec succès.";
}

?>

<!-- Formulaire de modification -->
<h2>Modifier le produit</h2>
<form action="edit_product.php?id=<?= $product['id'] ?>" method="post" enctype="multipart/form-data">
    <div class="form-group">
        <label for="name">Nom du produit</label>
        <input type="text" id="name" name="name" class="form-control" value="<?= $product['name'] ?>" required>
    </div>
    <div class="form-group">
        <label for="description">Description</label>
        <textarea id="description" name="description" class="form-control" required><?= $product['description'] ?></textarea>
    </div>
    <div class="form-group">
        <label for="price">Prix</label>
        <input type="number" id="price" name="price" class="form-control" value="<?= $product['price'] ?>" required>
    </div>
    <div class="form-group">
        <label for="image">Image actuelle</label><br>
        <img src="<?= $product['image'] ?>" alt="Image du produit" style="width: 150px; height: auto;"><br><br>
        <label for="image">Changer l'image</label>
        <input type="file" id="image" name="image" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Mettre à jour</button>
</form>
