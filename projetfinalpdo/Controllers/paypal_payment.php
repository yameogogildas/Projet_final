<?php
session_start();
require 'paypal_config.php';  // Inclure la configuration de PayPal

if (isset($_POST['total'])) {
    $total = $_POST['total'];

    $payer = new \PayPal\Api\Payer();
    $payer->setPaymentMethod('paypal');

    // Détails de la transaction
    $amount = new \PayPal\Api\Amount();
    $amount->setTotal($total)
           ->setCurrency('EUR'); // Devise en EUR

    $transaction = new \PayPal\Api\Transaction();
    $transaction->setAmount($amount)
                ->setDescription('Achat sur notre site');

    $redirectUrls = new \PayPal\Api\RedirectUrls();
    $redirectUrls->setReturnUrl('http://votre_site.com/execute_payment.php')
                 ->setCancelUrl('http://votre_site.com/cart.php');

    // Créer un paiement PayPal
    $payment = new \PayPal\Api\Payment();
    $payment->setIntent('sale')
            ->setPayer($payer)
            ->setTransactions([$transaction])
            ->setRedirectUrls($redirectUrls);

    try {
        $payment->create($apiContext);
        // Rediriger l'utilisateur vers PayPal
        header("Location: " . $payment->getApprovalLink());
        exit();
    } catch (Exception $e) {
        echo "Erreur lors de la création du paiement PayPal : " . $e->getMessage();
    }
}
