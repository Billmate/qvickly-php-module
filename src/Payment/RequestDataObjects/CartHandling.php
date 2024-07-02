<?php

namespace Qvickly\Api\Payment\RequestDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'taxrate',    type: 'decimal', precision: 2),
    StructureProperty(name: 'withouttax', type: 'decimal', precision: 2),
]
class CartHandling extends DataObject
{

}