<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$tokenParts = explode('.', $_ENV['TOKEN']);
$userData = json_decode(base64_decode($tokenParts[1]), true);

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$logos = $portalAPI->get('logos/' . $userData['Merchant']['id']);
if(is_array($logos) && array_key_exists('error', $logos)) {
    echo "Code: " . $logos['code'] . "\n";
    echo "Error: " . $logos['error'] . "\n";
    exit;
}
echo "Found " . count($logos) . " logos\n";
if(count($logos) > 0) {
    $oneLogo = array_pop($logos);
    echo "And the last logo is:\n";
    echo json_encode($oneLogo, JSON_PRETTY_PRINT);
}


