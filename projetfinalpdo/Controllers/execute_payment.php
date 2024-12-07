<?php
session_start();
include('paypal_config.php');

if (isset($_GET['paymentId']) && isset($_GET['PayerID'])) {
    $paymentId = $_GET['paymentId'];
    $payerId = $_GET['PayerID'];

    $payment = \PayPal\Api\Payment::get($paymentId, $apiContext);
    $execution = new \PayPal\Api\PaymentExecution();
    $execution->setPayerId($payerId);

    try {
        // Exécution du paiement
        $result = $payment->execute($execution, $apiContext);

        if ($result->getState() == 'approved') {
            // Si le paiement est réussi, traitez la commande
            echo "Le paiement a été effectué avec succès!";
            // Vide le panier et redirige vers la confirmation de commande
            unset($_SESSION['cart']);
            header('Location: order_confirmation.php');
            exit();
        }
    } catch (Exception $e) {
        echo "Erreur lors de l'exécution du paiement : " . $e->getMessage();
    }
} else {
    echo "Détails du paiement invalides.";
}
