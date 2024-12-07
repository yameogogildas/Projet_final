<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header('Location: ../Views/login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de commande</title>
</head>
<body>
    <h1>Votre commande a été enregistrée avec succès !</h1>
    <p>Merci pour votre achat. Vous recevrez bientôt une confirmation par email.</p>
    <a href="index.php">Retour à l'accueil</a>
</body>
</html>
