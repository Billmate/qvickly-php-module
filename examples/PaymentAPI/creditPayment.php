<?php
declare(strict_types=1);

require '../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

require '../../src/Payment/PaymentAPI.php';

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\DataObjects\Data;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true);
$data = new Data(
    [
        "number" => "12345"
    ]
);
$payment = $paymentAPI->creditPayment($data);
print_r($payment);