<?php
declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;
use Qvickly\Api\Auth\AuthAPI;
use Qvickly\Api\Portal\PortalAPI;
use Qvickly\Api\Payment\PaymentAPI;


$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$authAPI = new AuthAPI();
$auth = $authAPI->login($_ENV['USERNAME'], $_ENV['PASSWORD']);

$portalAPI = new PortalAPI($auth);

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);

$invoice = $portalAPI->get('invoices/' . $_ENV['INVOICEID']);

$hash = $invoice['hash'];

$result = $paymentAPI->updateStatusLogOfInvoiceByHash([
    'hash' => $hash,
    'body' => [
        'text' => 'Updated status log after getting hash from PortalAPI (%(sendtime))',
        'sendtime' => date(DATE_ATOM),
    ],
]);
print_r($result);