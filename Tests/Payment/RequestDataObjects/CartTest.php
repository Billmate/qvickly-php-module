<?php

namespace Payment\RequestDataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Cart;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{
    public function testCart()
    {
        $content = [
            'handling' => [
                'withouttax' => 100,
                'taxrate' => 25,
            ],
            'shipping' => [
                'withouttax' => 100,
                'taxrate' => 25,
            ],
            'total' => [
                'withouttax' => 200,
                'tax' => 50,
                'withtax' => 250,
                'rounding' => 0,
            ],
        ];
        $cart = new Cart($content);

        $cart->updateTotals(200, 50, 250, 0);
        $this->assertEquals($cart->validate([]), true);
        $this->assertIsObject($cart->Handling);
        $this->assertIsObject($cart->Shipping);
        $this->assertIsObject($cart->Total);
        $this->assertEquals($cart->unknownProperty, null);

        $exported = $cart->export(true);
        $this->assertIsArray($exported);
    }
}
