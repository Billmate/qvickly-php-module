<?php
declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\RequestDataObjects\PaymentData;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true);
$data = new Data(
    [
        "PaymentData" => new PaymentData([
            "comingFromPF2" => "1"
        ])
    ]
);
$payment = $paymentAPI->getAccountInfo($data);
print_r($payment);