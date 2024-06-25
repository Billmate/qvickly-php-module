<?php
declare(strict_types=1);

namespace Payment\DataObjects;

use PHPUnit\Framework\TestCase;
use Qvickly\Api\Payment\DataObjects\PaymentInfo;

class PaymentInfoTest extends TestCase
{
    public function testPaymentInfo()
    {
        $paymentInfo = new PaymentInfo();
        $this->assertIsBool($paymentInfo->validate());
    }
}
