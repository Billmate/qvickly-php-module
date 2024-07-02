<?php
declare(strict_types=1);

namespace Payment\DataObjects;

use PHPUnit\Framework\TestCase;
use Qvickly\Api\Payment\RequestDataObjects\PaymentInfo;

class PaymentInfoTest extends TestCase
{
    public function testPaymentInfo()
    {
        $paymentInfo = new PaymentInfo();
        $this->assertIsBool($paymentInfo->validate());
    }
}
