<?php

namespace Qvickly\Api\Payment\ResponseDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'paymentOptions', type: 'array'),
]
class PaymentOptions extends DataObject
{
    public function __construct(array|\stdClass $data = [])
    {
        if($data instanceof \stdClass) {
            $data = (array) $data;
        }
        foreach ($data as $value) {
            if (is_array($value) || $value instanceof \stdClass) {
                $this->data[] = new PaymentOption($value);
            } elseif ($value instanceof PaymentOption) {
                $this->data[] = $value;
            }
        }
    }
}