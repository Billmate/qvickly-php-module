<?php
declare(strict_types=1);

use Qvickly\Api\Auth\AuthAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$authAPI = new AuthAPI();
$step1 = $authAPI->bankidLogin(personalNumber: $_ENV['PNO'] ?? null);
if(is_array($step1) && array_key_exists('error', $step1)) {
    var_dump($step1);
    exit;
}
var_dump($step1);

sleep(5);

$cancel = $authAPI->bankidCancel($step1['orderRef']);
var_dump($cancel);