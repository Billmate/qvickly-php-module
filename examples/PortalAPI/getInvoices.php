<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$invoices = $portalAPI->get('invoices', params: ['filter' => 'search', 'search' => 'Testperson']);
echo "Found " . count($invoices) . " invoices\n";
if(count($invoices) > 0) {
    $oneInvoice = array_pop($invoices);
    $oneInvoiceId = $oneInvoice['invoiceid_real'];
    $invoice = $portalAPI->get('invoices/' . $oneInvoiceId);
    echo "And the last invoice is:\n";
    echo json_encode($invoice, JSON_PRETTY_PRINT);
}


