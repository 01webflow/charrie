<?php
// success.php

// Include necessary libraries for payment processing
require 'vendor/autoload.php';
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

// Get payment ID from the query string
$paymentId = $_GET['paymentId'];
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        'YOUR_CLIENT_ID',
        'YOUR_CLIENT_SECRET'
    )
);

// Get the payment
$payment = Payment::get($paymentId, $apiContext);

// Execute payment
$execution = new PaymentExecution();
$execution->setPayerId($_GET['PayerID']);
try {
    $result = $payment->execute($execution, $apiContext);
    // Payment successful, send confirmation email
    $to = $_POST['email'];
    $subject = "Thank You for Your Donation";
    $message = "Dear " . $_POST['name'] . ",\n\nThank you for your generous donation of $" . $_POST['amount'] . " to our organization. Your support helps us make a difference.\n\nBest regards,\nWawa Seed Africa Foundation";
    mail($to, $subject, $message);
    echo "Thank you for your donation! Your payment has been processed successfully.";
} catch (Exception $ex) {
    die($ex);
}
?>