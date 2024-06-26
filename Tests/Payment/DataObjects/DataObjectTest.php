<?php

namespace Payment\DataObjects;

use Qvickly\Api\Payment\DataObjects\DataObject;
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
        $dataObject = new DataObject($data);

        $this->assertEquals('value1', $dataObject['key']);
        $this->assertEquals('value2', $dataObject['PaymentData:key']);
        $this->assertEquals('value1', $dataObject->key);
        $this->assertEquals('value2', $dataObject['PaymentData:key']);
        var_dump($dataObject);
    }

    public function test__set()
    {
        $dataObject = new DataObject();
        $dataObject[] = 'value1';
        $dataObject[] = 'value2';

        $this->assertEquals('value1', $dataObject['0']);
        $this->assertEquals('value2', $dataObject['1']);
    }
}
