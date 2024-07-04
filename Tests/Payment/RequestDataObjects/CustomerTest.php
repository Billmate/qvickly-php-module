<?php

namespace Payment\RequestDataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Customer;
use PHPUnit\Framework\TestCase;

class CustomerTest extends TestCase
{

    public function testCustomer()
    {
        $customer = new Customer();
        $customer->nr = 12;
        $customer->pno = '550108-1018';
        $this->assertTrue($customer->validate());
    }
}
