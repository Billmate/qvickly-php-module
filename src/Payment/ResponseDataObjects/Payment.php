<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\ResponseDataObjects;


use Qvickly\Api\Payment\RequestDataObjects\DataObject;
use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'number',     type: 'int', exportAs: 'string'),
    StructureProperty(name: 'status',     type: 'string'),
    StructureProperty(name: 'orderid',    type: 'string'),
    StructureProperty(name: 'url',        type: 'string'),
    ]
class Payment extends DataObject
{
}