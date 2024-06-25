<?php

namespace Qvickly\Api\Payment\DataObjects;

#[
    StructureProperty(name: 'tax', type: 'decimal', precision: 2),
    StructureProperty(name: 'withouttax', type: 'decimal', precision: 2),
    StructureProperty(name: 'rounding', type: 'decimal', precision: 2),
    StructureProperty(name: 'withtax', type: 'decimal', precision: 2),
]
class CartTotal extends DataObject
{

}