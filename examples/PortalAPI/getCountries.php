<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$countries = $portalAPI->get('countries');
echo "Found " . count($countries) . " countries\n";
if(count($countries) > 0) {
    $oneCountry = array_pop($countries);
    echo "And the last country is:\n";
    echo json_encode($oneCountry, JSON_PRETTY_PRINT);
}


