<?php
declare(strict_types=1);

use Qvickly\Api\Auth\AuthAPI;

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
$dotenv->load();

$authAPI = new AuthAPI();

$token = $authAPI->login($_ENV['USERNAME'], $_ENV['PASSWORD']);
$signature = '';

$step1 = $authAPI->bankidSign($token, "test", "sv", "Detta ar ett test");
if(is_array($step1) && array_key_exists('error', $step1)) {
    var_dump($step1);
    exit;
}
var_dump($step1);

for ($i = 0; $i < 29; $i++) {
    echo "Loop $i\n";
    // Find hash code
    $signLink = $step1['signList'][$i];
    // Create QR Code from auth code
    // ... create the QR Code here
    system("qrencode -o /tmp/test.png '$signLink'"); // Replace this with your QR Code generator
    // Show the QR Code to the user
    // ... show the QR Code here
    system("open '/tmp/test.png'"); // Replace this with your QR Code viewer
    // Wait for the user to scan the QR Code
    sleep(1);
    // Check if the user has scanned the QR Code
    $step2 = $authAPI->bankidSignCollect($token, $step1['orderRef']);
    var_dump($step2);
    if(is_array($step2) && array_key_exists('status', $step2)) {
        if($step2['status'] === 'complete') {
            echo "BankID sign complete\n";
            $signature = $step2['completionData']['signature'];
            break;
        } elseif ($step2['status'] === 'failed') {
            throw new Exception('Failed to collect bankid');
        } elseif ($step2['status'] === 'pending') {
            echo "Waiting for user to auth with BankID\n";
        } else {
            throw new Exception('Failed to collect bankid');
        }
    } else {
        throw new Exception('Failed to collect bankid');
    }
}

if($signature === '') {
    throw new Exception('Failed to sign with bankid');
}

echo "Signature: $signature\n";
