<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\RequestDataObjects\Credentials;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\RequestDataObjects\Payload;
use PHPUnit\Framework\TestCase;

class PayloadTest extends TestCase
{
    public function testPayloadFromArray()
    {
        $payload = new Payload([
            'credentials' => [
                'id' => 123456,
                'hash' => '123'
            ],
            'data' => [
                'taxrate' => 0.25,
                'withouttax' => 100,
                'title' => 'Test',
            ],
            'function' => 'test'
        ]);
        $this->assertInstanceOf(Payload::class, $payload);

        $this->assertTrue($payload->validate());
    }

    public function testPayloadFromObject()
    {
        $payload = new Payload([
            'credentials' => new Credentials([
                'id' => 123456,
                'hash' => '123'
            ]),
            'data' => new Data([
                'taxrate' => 0.25,
                'withouttax' => 100,
                'title' => 'Test',
            ]),
            'function' => 'test'
        ]);
        $this->assertInstanceOf(Payload::class, $payload);

        $this->assertTrue($payload->validate());
    }

    public function testPayloadExport()
    {
        $payload = new Payload([
            'credentials' => new Credentials([
                'id' => 123456,
                'hash' => '123'
            ]),
            'data' => new Data([
                'taxrate' => 0.25,
                'withouttax' => 100,
                'title' => 'Test',
            ]),
            'function' => 'test'
        ]);
        $this->assertInstanceOf(Payload::class, $payload);

        $this->assertTrue($payload->validate());

        $export = $payload->export();
        $this->assertIsArray($export);
        $this->assertArrayHasKey('credentials', $export);
        $this->assertArrayHasKey('data', $export);
        $this->assertArrayHasKey('function', $export);
    }
}
