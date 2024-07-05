<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$customers = $portalAPI->get('customers', params: ['filter' => 'search', 'search' => 'Testperson']);
echo "Found " . count($customers) . " customers\n";
if(count($customers) > 0) {
    $oneCustomer = array_pop($customers);
    $oneCustomerId = $oneCustomer['mexcParamvaluesetsid'];
    $customer = $portalAPI->get('customers/' . $oneCustomerId);
    echo "And the last customer is:\n";
    echo json_encode($customer, JSON_PRETTY_PRINT);
}


