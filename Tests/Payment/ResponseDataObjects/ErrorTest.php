<?php

namespace Payment\Errors;

use Qvickly\Api\Enums\ReturnDataType;
use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\ResponseDataObjects\Error;
use PHPUnit\Framework\TestCase;

class ErrorTest extends TestCase
{

        public function testError()
        {
            $api = new PaymentAPI('123', '123', testMode: true, returnData: ReturnDataType::PROCESSED);
            $response = $api->activatePayment(new Data(["number" => 1234657890123]));
            $this->assertInstanceOf(Error::class, $response);
            $this->assertTrue($response->isError);
        }
}
