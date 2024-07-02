<?php

namespace Qvickly\Api\Payment\ResponseDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'method', type: 'int', exportAs: 'string'),
    StructureProperty(name: 'currency', type: 'string'),
    StructureProperty(name: 'language', type: 'string'),
    StructureProperty(name: 'minAmount', type: 'int', exportAs: 'string'),
    StructureProperty(name: 'customertypes', type: 'array'),
    StructureProperty(name: 'cards', type: 'array'),
    StructureProperty(name: 'companySigning', type: 'boolnum'),
    StructureProperty(name: 'consumerFee', type: 'int', exportAs: 'string'),
    StructureProperty(name: 'quickpayment', type: 'boolnum'),
    StructureProperty(name: 'mode', type: 'int', exportAs: 'string'),
]
class PaymentOption extends DataObject
{
}