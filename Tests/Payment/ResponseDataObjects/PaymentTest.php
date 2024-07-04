<?php

namespace Payment\ResponseDataObjects;

use Qvickly\Api\Enums\ReturnDataType;
use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\ResponseDataObjects\Payment;
use PHPUnit\Framework\TestCase;

use function Qvickly\Api\Payment\Helpers\examplePayment;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../..');
$dotenv->load();

class PaymentTest extends TestCase
{
    public function testPaymentAndCancel()
    {
        $api = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true, returnData: ReturnDataType::PROCESSED);
        $data = examplePayment(2, 'tess.t.person@example.com');
        $payment = $api->addPayment($data);
        $this->assertEquals($payment->validate(), true);
        $this->assertInstanceOf(Payment::class, $payment);
        $cancelPayment = $api->cancelPayment(["number" => $payment->number]);
        $this->assertInstanceOf(Payment::class, $cancelPayment);
        $this->assertEquals($cancelPayment->status, 'Cancelled');
    }

    public function testActivateAndCreditPayment()
    {
        $api = new PaymentAPI($_ENV['EID'], $_ENV['SECRET'], testMode: true, returnData: ReturnDataType::PROCESSED);
        $data = examplePayment(2, 'tess.t.person@example.com');
        $payment = $api->addPayment($data);
        $this->assertInstanceOf(Payment::class, $payment);
        $this->assertEquals($payment->status, 'Created');
        $activatedPayment = $api->activatePayment(["number" => $payment->number]);
        $this->assertInstanceOf(Payment::class, $activatedPayment);
        $this->assertEquals($activatedPayment->status, 'Handling');
        $creditPayment = $api->creditPayment(["number" => $activatedPayment->number]);
        $this->assertInstanceOf(Payment::class, $creditPayment);
        $this->assertEquals($creditPayment->status, 'Credited');
    }
}
