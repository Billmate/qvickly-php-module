<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$creditflow = $portalAPI->get('creditflow/search/5501011018');
if(is_array($creditflow) && array_key_exists('error', $creditflow)) {
    echo "Code: " . $creditflow['code'] . "\n";
    echo "Error: " . $creditflow['error'] . "\n";
    exit;
}
echo json_encode($creditflow, JSON_PRETTY_PRINT);


