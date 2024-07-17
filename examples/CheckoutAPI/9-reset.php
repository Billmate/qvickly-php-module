<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

use Qvickly\Api\Checkout\CheckoutAPI;

use function Qvickly\Api\Payment\Helpers\exampleCheckout;
use Qvickly\Api\Enums\PaymentMethod;

$checkoutAPI = new CheckoutAPI($_ENV['EID'], $_ENV['SECRET'], true, [
    'BASE_URL' => 'https://api.development.billmate.se/',
    'CHECKOUT_BASE_URL' => 'https://checkout.development.billmate.se/',
]);

echo "Create checkout\n";
$payload = exampleCheckout();
$checkout = $checkoutAPI->initCheckout($payload);

echo "Reset\n";
$reset = $checkoutAPI->reset($checkout['hash']);

echo json_encode($reset, JSON_PRETTY_PRINT) . "\n";

echo "URL to use: " . $checkout['url'] . "\n";
