<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$whitelists = $portalAPI->get('whitelists');
echo "Found " . count($whitelists) . " whitelists\n";
var_dump($whitelists);
if(count($whitelists) > 0) {
    $oneWhitelist = array_pop($whitelists);
    echo "And the last whitelist is:\n";
    echo json_encode($oneWhitelist, JSON_PRETTY_PRINT);
}


