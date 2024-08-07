<?php
declare(strict_types=1);

namespace Payment\RequestDataObjects;

use Qvickly\Api\Payment\RequestDataObjects\PaymentData;
use PHPUnit\Framework\TestCase;

class PaymentDataTest extends TestCase
{
    public function testPaymentData()
    {
        $paymentData = new PaymentData();
        $this->assertIsBool($paymentData->validate());
    }
}
