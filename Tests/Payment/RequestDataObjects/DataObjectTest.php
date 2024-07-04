<?php

namespace Payment\RequestDataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Data;
use PHPUnit\Framework\TestCase;

class DataObjectTest extends TestCase
{
    public function test__get()
    {
        $data = [
            'key' => 'value1',
            'PaymentData' => [
                'key' => 'value2',
            ],
        ];
        $dataObject = new Data($data);

        $this->assertEquals('value1', $dataObject['key']);
        $this->assertEquals('value2', $dataObject['PaymentData:key']);
        $this->assertEquals('value1', $dataObject->key);
        $this->assertEquals('value2', $dataObject['PaymentData:key']);
    }

    public function test__set()
    {
        $dataObject = new Data();
        $dataObject[] = 'value1';
        $dataObject[] = 'value2';

        $this->assertEquals('value1', $dataObject['0']);
        $this->assertEquals('value2', $dataObject['1']);
    }

    public function testUpdateCartWithoutExistingCart()
    {
        $data = [
            'Articles' => [
                [
                    'taxrate' => 25.00,
                    'withouttax' => 100.00,
                    'artnr' => '123456',
                    'title' => 'Test Product',
                    'quantity' => 1.00,
                    'aprice' => 125.00,
                    'discount' => 0.00
                ],
                [
                    'taxrate' => 25.00,
                    'withouttax' => 100.00,
                    'artnr' => '123456',
                    'title' => 'Test Product',
                    'quantity' => 1.00,
                    'aprice' => 125.00,
                    'discount' => 0.00
                ]
            ]
        ];
        $dataObject = new Data($data);
        $dataObject->updateCart();
        $this->assertEquals(200, $dataObject->Cart->Total->withouttax);
        $this->assertEquals(50, $dataObject->Cart->Total->tax);
        $this->assertEquals(250, $dataObject->Cart->Total->withtax);
    }
    public function testUpdateCartWithExistingCart()
    {
        $data = [
            'Articles' => [
                [
                    'taxrate' => 25.00,
                    'withouttax' => 100.00,
                    'artnr' => '123456',
                    'title' => 'Test Product',
                    'quantity' => 1.00,
                    'aprice' => 125.00,
                    'discount' => 0.00
                ],
                [
                    'taxrate' => 25.00,
                    'withouttax' => 100.00,
                    'artnr' => '123456',
                    'title' => 'Test Product',
                    'quantity' => 1.00,
                    'aprice' => 125.00,
                    'discount' => 0.00
                ]
            ],
            'Cart' => [
                'Total' => [
                    'withouttax' => 100,
                    'tax' => 25,
                    'withtax' => 125
                ]
            ]
        ];
        $dataObject = new Data($data);
        $dataObject->updateCart();
        $this->assertEquals(200, $dataObject->Cart->Total->withouttax);
        $this->assertEquals(50, $dataObject->Cart->Total->tax);
        $this->assertEquals(250, $dataObject->Cart->Total->withtax);
    }

    public function testAddArticle()
    {
        $data = new Data();
        $data->addArticle([
            'taxrate' => 25.00,
            'withouttax' => 100.00,
            'artnr' => '123456',
            'title' => 'Test Product',
            'quantity' => 1.00,
            'aprice' => 125.00,
            'discount' => 0.00
        ]);
        $data->addArticle([
            'taxrate' => 25.00,
            'withouttax' => 100.00,
            'artnr' => '123456',
            'title' => 'Test Product',
            'quantity' => 1.00,
            'aprice' => 125.00,
            'discount' => 0.00
        ]);
        $data->updateCart();
        $this->assertEquals(2, count($data->Articles));
        $this->assertEquals(200, $data->Cart->Total->withouttax);
    }
}
