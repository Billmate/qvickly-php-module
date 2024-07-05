<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$currencies = $portalAPI->get('currencies');
echo "Found " . count($currencies) . " currencies\n";
if(count($currencies) > 0) {
    $oneCurrency = array_pop($currencies);
    echo "And the last currency is:\n";
    echo json_encode($oneCurrency, JSON_PRETTY_PRINT);
}


