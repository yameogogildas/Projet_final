<?php
//Voici le fichier db.php qui gère la connexion à votre base de données projetpdo :
$host = 'localhost';
$dbname = 'projetpdo';  // Votre base de données
$username = 'root';      // Nom d'utilisateur MySQL
$password = '';          // Mot de passe MySQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Could not connect to the database $dbname: " . $e->getMessage());
}
?>
