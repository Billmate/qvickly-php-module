<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\DataObjects\Data;
use PHPUnit\Framework\TestCase;

class DataTest extends TestCase
{
    public function testCart()
    {
        $data = new Data();
        $data->addArticle([
            'artno' => '123456',
            'name' => 'Test',
            'aprice' => 100,
            'withouttax' => 200,
            'taxrate' => 25,
            'quantity' => 2
        ]);
        $data->updateCart();
        $this->assertEquals(200, $data->Cart->total->withouttax);
        $this->assertEquals(50, $data->Cart->total->tax);
        $this->assertEquals(250, $data->Cart->total->withtax);
    }
}
