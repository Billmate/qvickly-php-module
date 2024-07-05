<?php
declare(strict_types=1);

use Qvickly\Api\Portal\PortalAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$portalAPI = new PortalAPI($_ENV['TOKEN'], testMode: true);
$reports = $portalAPI->get('reports');
echo "Found " . count($reports) . " reports\n";
if(count($reports) > 0) {
    $oneReport = array_pop($reports);
    $oneReportId = $oneReport['mexcCustomerjournalsid'];
    $report = $portalAPI->get('reports/' . $oneReportId);
    echo "And the last report is:\n";
    echo json_encode($report, JSON_PRETTY_PRINT);
}


