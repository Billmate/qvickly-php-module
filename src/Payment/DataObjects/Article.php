<?php

namespace Qvickly\Api\Payment\DataObjects;

#[
    StructureProperty(name: 'taxrate', type: 'decimal', precision: 2),
    StructureProperty(name: 'withouttax', type: 'decimal', precision: 2),
    StructureProperty(name: 'artnr', type: 'string'),
    StructureProperty(name: 'title', type: 'string'),
    StructureProperty(name: 'quantity', type: 'decimal', precision: 2),
    StructureProperty(name: 'aprice', type: 'decimal', precision: 2),
    StructureProperty(name: 'discount', type: 'decimal', precision: 2),
    ]
class Article extends DataObject
{

}