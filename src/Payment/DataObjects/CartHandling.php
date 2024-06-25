<?php

namespace Qvickly\Api\Payment\DataObjects;

#[
    StructureProperty(name: 'taxrate', type: 'decimal', precision: 2),
    StructureProperty(name: 'withouttax', type: 'decimal', precision: 2),
]
class CartHandling extends DataObject
{

}