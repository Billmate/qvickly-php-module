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

$invoices = $portalAPI->get('invoices?filter=status,search,invoiceType&status=created&search=tess&invoicetype=F&limit=5');

echo "Found " . count($invoices) . " invoices to activate.\n";

foreach($invoices as $invoice) {
    echo "Fetching invoice {$invoice['invoiceid_real']}...\n";
    $payment = $paymentAPI->getPaymentInfo([ 'number' => $invoice['invoiceid_real']]);
    echo json_encode($payment, JSON_PRETTY_PRINT) . "\n\n";
}
