<?php

namespace Qvickly\Api\Payment\RequestDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'taxrate',    type: 'decimal', required: true, exportAs: 'string'),
    StructureProperty(name: 'withouttax', type: 'decimal', required: true, exportAs: 'string'),
    StructureProperty(name: 'artnr',      type: 'string'),
    StructureProperty(name: 'title',      type: 'string',                      required: true),
    StructureProperty(name: 'quantity',   type: 'decimal', exportAs: 'string'),
    StructureProperty(name: 'aprice',     type: 'decimal', exportAs: 'string'),
    StructureProperty(name: 'discount',   type: 'decimal', exportAs: 'string'),
]
class Article extends DataObject
{

}