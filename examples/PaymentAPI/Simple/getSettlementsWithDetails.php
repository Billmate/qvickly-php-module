<?php
declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "settlementId" => "1"
    ]
);
$settlement = $paymentAPI->getSettlementsWithDetails($data);
print_r($settlement);