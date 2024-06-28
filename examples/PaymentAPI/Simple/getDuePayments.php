<?php
declare(strict_types=1);

require '../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\DataObjects\Data;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$payments = $paymentAPI->getDuePayments();
print_r($payments);