<?php

namespace Qvickly\Api\Payment\DataObjects;

#[
    StructureProperty(name: 'taxrate',    type: 'decimal', exportAs: 'string', required: true),
    StructureProperty(name: 'withouttax', type: 'decimal', exportAs: 'string', required: true),
    StructureProperty(name: 'artnr',      type: 'string'),
    StructureProperty(name: 'title',      type: 'string',                      required: true),
    StructureProperty(name: 'quantity',   type: 'decimal', exportAs: 'string'),
    StructureProperty(name: 'aprice',     type: 'decimal', exportAs: 'string'),
    StructureProperty(name: 'discount',   type: 'decimal', exportAs: 'string'),
]
class Article extends DataObject
{

}