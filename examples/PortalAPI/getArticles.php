<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$articles = $portalAPI->get('articles', params: ['filter' => 'searchArticleNumber', 'articlenr' => 'test']);
if(is_array($articles) && array_key_exists('error', $articles)) {
    echo "Code: " . $articles['code'] . "\n";
    echo "Error: " . $articles['error'] . "\n";
    exit;
}
echo "Found " . count($articles) . " articles\n";
if(count($articles) > 0) {
    $oneArticle = array_pop($articles);
    $oneArticleId = $oneArticle['mexcParamvaluesetsid'];
    $article = $portalAPI->get('articles/' . $oneArticleId);
    echo "And the last article is:\n";
    echo json_encode($article, JSON_PRETTY_PRINT);
}


