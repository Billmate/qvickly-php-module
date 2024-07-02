<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Data;
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

        $this->assertEquals(200, $data->Cart->Total->withouttax);
        $this->assertEquals(50, $data->Cart->Total->tax);
        $this->assertEquals(250, $data->Cart->Total->withtax);
    }

    public function testCartWithRawData()
    {
        $rawData = [
            'Articles' => [
                [
                    'artno' => '123456',
                    'name' => 'Test',
                    'aprice' => 100,
                    'withouttax' => 200,
                    'taxrate' => 25,
                    'quantity' => 2
                ]
            ],
            'Cart' => [
                'Total' => [
                    'withouttax' => 200,
                    'tax' => 50,
                    'withtax' => 250
                ]
            ]
        ];
        $data = new Data($rawData);

        $this->assertEquals(200, $data->Cart->Total->withouttax);
        $this->assertEquals(50, $data->Cart->Total->tax);
        $this->assertEquals(250, $data->Cart->Total->withtax);
    }

}
