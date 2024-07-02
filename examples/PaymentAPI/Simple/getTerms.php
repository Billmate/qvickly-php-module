<?php
declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\RequestDataObjects\PaymentData;
use Qvickly\Api\Payment\RequestDataObjects\Cart;
use Qvickly\Api\Payment\RequestDataObjects\CartTotal;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "PaymentData" => new PaymentData([
            "method" => 1
        ]),
        "Cart" => new Cart([
            "total" => new CartTotal([
                "withtax" => 100000
            ])
        ])
    ]
);
$terms = $paymentAPI->getTerms($data);
print_r($terms);