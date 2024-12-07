<?php
// Configuration de l'API PayPal
require 'vendor/autoload.php'; // Assurez-vous d'installer la bibliothèque PayPal avec Composer

// Remplissez les informations suivantes avec celles obtenues depuis votre compte PayPal
$clientId = 'Votre_Client_ID';
$clientSecret = 'Votre_Client_Secret';

// Configurer l'APIContext pour les paiements PayPal
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $clientId,     // Identifiant client
        $clientSecret  // Secret client
    )
);

// Si vous souhaitez utiliser l'environnement sandbox de PayPal (test), vous pouvez définir l'URL de l'API comme suit :
$apiContext->setConfig(
    array(
        'mode' => 'sandbox' // Utilisez 'live' en production
    )
);
?>
