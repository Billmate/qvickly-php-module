<?php
declare(strict_types=1);

use Qvickly\Api\Enums\PaymentMethod;
use Qvickly\Api\Paylink\PaylinkAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Qvickly\Api\Payment\RequestDataObjects\Customer;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\RequestDataObjects\Articles;
use Qvickly\Api\Payment\RequestDataObjects\PaymentData;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$paylinkAPI = new PaylinkAPI($_ENV['EID'], $_ENV['SECRET']);

$articles = new Articles();
$articles->addArticle([
    'quantity' => 1,
    'title' => 'Test product',
    'withouttax' => 100,
    'taxrate' => 25,
    'artnr' => '1234',
]);

$customer = new Customer([
    'pno' => $_ENV['PNO'],
]);
$customer->setBillingAddress([
    'firstname' => 'Tess T',
    'lastname' => 'Person',
    'type' => 'person',
    'street' => 'Testgatan 1',
    'email' => $_ENV['EMAIL'],
    'zip' => $_ENV['ZIP'],
    'city' => $_ENV['CITY'],
    'phonenumber' => $_ENV['PHONENUMBER'],
]);

$payload = new Data([
    'PaymentData' => new PaymentData([
        'method' => PaymentMethod::PAYLINK->value,
        'country' => 'SE',
        'language' => 'sv',
        'currency' => 'SEK',
        'orderid' => date('YmdHis'),
        'autocancel' => 2880,
        'callbackurl' => 'https://example.com/callback',
        'accepturl' => 'https://example.com/accept',
        'cancelurl' => 'https://example.com/cancel',
        'bankid' => true,
    ]),
    'Customer' => $customer,
    'Articles' => $articles,
]);

$payment = $paylinkAPI->create($payload);
echo json_encode($payment, JSON_PRETTY_PRINT) . "\n";

echo "URL to use: " . $payment['url'] . "\n";