<?php
declare(strict_types=1);

require '../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../QvicklyModule');
$dotenv->load();

'../../src/Payment/PaymentAPI.php';

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\DataObjects\Data;
use Qvickly\Api\Payment\DataObjects\PaymentData;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "PaymentData" => new PaymentData(
            [
                "country" => "SE",
                "currency" => "SEK",
                "language" => "sv",
            ]
        )
    ]
);
$plans = $paymentAPI->getPaymentPlans($data);
print_r($plans);