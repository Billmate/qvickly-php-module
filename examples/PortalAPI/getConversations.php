<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$conversations = $portalAPI->get('conversations');
echo "Found " . count($conversations) . " conversations\n";
if(count($conversations) > 0) {
    $oneConversation = array_pop($conversations);
    $oneConversationId = $oneConversation['mexcParamvaluesetsid'];
    $conversation = $portalAPI->get('conversations/' . $oneConversation);
    echo "And the last conversation is:\n";
    echo json_encode($conversation, JSON_PRETTY_PRINT);
}


