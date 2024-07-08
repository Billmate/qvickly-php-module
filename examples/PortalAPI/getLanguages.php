<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$languages = $portalAPI->get('languages');
if(is_array($languages) && array_key_exists('error', $languages)) {
    echo "Code: " . $languages['code'] . "\n";
    echo "Error: " . $languages['error'] . "\n";
    exit;
}
echo "Found " . count($languages) . " languages\n";
if(count($languages) > 0) {
    $oneLanguage = array_pop($languages);
    echo "And the last language is:\n";
    echo json_encode($oneLanguage, JSON_PRETTY_PRINT);
}


