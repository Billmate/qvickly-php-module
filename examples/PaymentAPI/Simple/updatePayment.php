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

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true);
$paymentData = new PaymentData(
    [
        "number" => "4003523",
        "method" => "2",
        "currency" => "SEK",
        "language" => "sv",
        "country" => "SE",
        "orderid" => "12345abcde",
        "bankid" => "true",
        "accepturl" => "https://example.com/accept",
        "cancelurl" => "https://example.com/cancel",
        "callbackurl" => "https://example.com/callback",
        "autocancel" => "2800",
    ]
);
$billing = new BillingAddress(
    [
        "firstname" => "Test",
        "lastname" => "Testsson",
        "street" => "Testgatan 1",
        "zip" => "12345",
        "city" => "Teststad",
        "country" => "SE",
        "email" => "test@example.com",
        "phone" => "0700000000",
    ]);
$customer = new Customer(
    [
        "pno" => "550101-1018",
        "Billing" => $billing,
    ]);
$data = new Data(
    [
        "PaymentData" => $paymentData,
        "Customer" => $customer
    ]
);
$data->addArticle([
    "artnr" => "1",
    "title" => "Test",
    "aprice" => "10000",
    "taxrate" => "25",
    "quantity" => "2",
    "withouttax" => "20000"
]);
$data->updateCart();
$payment = $paymentAPI->updatePayment($data);
print_r($payment);