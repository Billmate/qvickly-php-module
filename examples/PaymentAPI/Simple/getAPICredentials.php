<?php
declare(strict_types=1);

require '../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\DataObjects\Data;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "hash" => "123456abc123456abc123456abc12345",
        "eid" => "23456",
    ]
);
$credentials = $paymentAPI->getAPICredentials($data);
print_r($credentials);