<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

use Qvickly\Api\Checkout\CheckoutAPI;

use function Qvickly\Api\Payment\Helpers\exampleCheckout;

$checkoutAPI = new CheckoutAPI($_ENV['EID'], $_ENV['SECRET'], true, [
//    'BASE_URL' => 'https://api.development.billmate.se/',
//    'CHECKOUT_BASE_URL' => 'https://checkout.development.billmate.se/',
]);

echo "Create checkout\n";
$payload = exampleCheckout();
$checkout = $checkoutAPI->initCheckout($payload);

echo "Step 1\n";
$personalInfo = $checkoutAPI->step1($checkout['hash'], [
    'pno' => $_ENV['PNO'],
    'email' => $_ENV['EMAIL'],
    'type' => 'person',
    'zip' => $_ENV['ZIP'],
    'phonenumber' => $_ENV['PHONENUMBER'],
]);

echo "Update shipping address\n";
$shippingAddress = $checkoutAPI->updateShippingAddress($checkout['hash'], [
    'firstname' => 'FIRSTNAME',
    'lastname' => 'LASTNAME',
    'street' => 'STREET',
    'zip' => '23456',
    'city' => 'CITY',
    'country' => 'COUNTRY',
]);

echo json_encode($shippingAddress, JSON_PRETTY_PRINT) . "\n";

echo "URL to use: " . $checkout['url'] . "\n";