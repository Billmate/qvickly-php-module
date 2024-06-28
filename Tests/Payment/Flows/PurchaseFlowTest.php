<?php
declare(strict_types=1);

namespace Payment\Flows;

require __DIR__ . '/../../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

use PHPUnit\Framework\TestCase;
use Qvickly\Api\Payment\PaymentAPI;

use function Qvickly\Api\Payment\Helpers\examplePayment as examplePayment;

class PurchaseFlowTest extends TestCase
{
    public function testAddActivate()
    {
        $api = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true);
        $paymentData = examplePayment(2, "thomas.bjork@qvickly.io");
        $paymentData->updateCart(true);
        $payment = $api("addPayment", $paymentData);
        $orderRef = $payment->number;
        var_dump($payment);
        echo "Looking out for orderRef: $orderRef\n";
        $this->assertIsNumeric($orderRef);
    }

}
