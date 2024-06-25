<?php
declare(strict_types=1);

namespace Payment\DataObjects;

use Qvickly\Api\Payment\DataObjects\PaymentData;
use PHPUnit\Framework\TestCase;

class PaymentDataTest extends TestCase
{
    public function testPaymentData()
    {
        $paymentData = new PaymentData();
        $this->assertIsBool($paymentData->validate());
    }
}
