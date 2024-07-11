<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

use Qvickly\Api\Checkout\CheckoutAPI;

use function Qvickly\Api\Payment\Helpers\exampleCheckout;

$checkoutAPI = new CheckoutAPI($_ENV['EID'], $_ENV['SECRET'], true);

$payload = exampleCheckout();
$checkout = $checkoutAPI->initCheckout($payload);

echo "Update email\n";
var_dump($checkoutAPI->updateBillingEmail($checkout['hash'], 'thomas.bjork@qvickly.io'));

echo "Update pno\n";
var_dump($checkoutAPI->updatePno($checkout['hash'], '6805154918'));

echo "Done\n";
var_dump($checkout);