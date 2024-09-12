<?php
declare(strict_types=1);

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use \Qvickly\Api\Payment\RequestDataObjects\KalpForm;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);

$kalpData = new KalpForm(
    [
        "monthlyIncome" => "32000",
        "nbrOfPerson" => "2",
        "typeOfAccommodation" => "rental",
        "monthlyExpenses" => "2300",
        "monthlyLoans" => "2500",
    ]
);
$paymentData = new \Qvickly\Api\Payment\RequestDataObjects\PaymentData((
    [
        "paymentplanid" => "1",
    ]
));
$data = new Data(
    [
        "kalpData" => $kalpData,
        "PaymentData" => $paymentData,
        "number" => 123456
    ]
);
$payment = $paymentAPI->uploadKalpForm($data);
print_r($payment);