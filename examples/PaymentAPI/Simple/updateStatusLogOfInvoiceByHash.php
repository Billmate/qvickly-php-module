<?php
declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\RequestDataObjects\Customer;
use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use \Qvickly\Api\Payment\RequestDataObjects\PaymentData;
use \Qvickly\Api\Payment\RequestDataObjects\BillingAddress;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "hash" => $_ENV['INVOICEHASH'],
        "body" => [
            "text" => "Updated status log from API",
        ],
    ]
);
$result = $paymentAPI->updateStatusLogOfInvoiceByHash($data);
print_r($result);