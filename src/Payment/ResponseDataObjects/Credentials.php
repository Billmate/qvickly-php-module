<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\ResponseDataObjects;


use Qvickly\Api\Payment\RequestDataObjects\DataObject;
use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'id',       type: 'string', required: true),
    StructureProperty(name: 'hash',     type: 'string', required: true),
    StructureProperty(name: 'version',  type: 'string'),
    StructureProperty(name: 'client',   type: 'string', default: 'Qvickly'),
    StructureProperty(name: 'language', type: 'string'),
    StructureProperty(name: 'test',     type: 'string', exportAs: 'boolstr'),
    StructureProperty(name: 'time',     type: 'string'),
    ]
class Credentials extends DataObject
{
}