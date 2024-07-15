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

echo "Step 1\n";
$personalInfo = $checkoutAPI->step1($checkout['hash'], [
    'pno' => $_ENV['PNO'],
    'email' => $_ENV['EMAIL'],
    'type' => 'person',
    'zip' => $_ENV['ZIP'],
    'phonenumber' => $_ENV['PHONENUMBER'],
]);

echo "Payment plans\n";
$paymentplans = $checkoutAPI->getpaymentplans($checkout['hash']);

echo "Payment methods\n";
$paymentMethods = $checkoutAPI->getPaymentMethods($checkout['hash']);

echo "Update payment method\n";
$paymentMethod = $checkoutAPI->updatePaymentMethod($checkout['hash'], PaymentMethod::CARD->value);

echo "Get payment\n";
$get = $checkoutAPI->get($checkout['hash']);

echo "Validate payment\n";
$validate = $checkoutAPI->validate($checkout['hash'], $get);
echo json_encode($validate, JSON_PRETTY_PRINT) . "\n";

echo "URL to use: " . $validate['url'] . "\n";