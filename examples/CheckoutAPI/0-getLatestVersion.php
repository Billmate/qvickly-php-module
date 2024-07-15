<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

use Qvickly\Api\Checkout\CheckoutAPI;

$checkoutAPI = new CheckoutAPI($_ENV['EID'], $_ENV['SECRET'], overrides: [
//    'BASE_URL' => 'https://api.development.billmate.se/',
//    'CHECKOUT_BASE_URL' => 'https://checkout.development.billmate.se/',
]);

echo "Get version\n";
$version = $checkoutAPI->getVersion();

echo "Current Checkout version is " . $version . "\n";
