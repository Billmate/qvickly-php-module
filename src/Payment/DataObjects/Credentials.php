<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;


#[
    StructureProperty(name: 'id', type: 'string', required: true),
    StructureProperty(name: 'hash', type: 'string', required: true),
    StructureProperty(name: 'version', type: 'string'),
    StructureProperty(name: 'client', type: 'string', default: 'Qvickly'),
    StructureProperty(name: 'language', type: 'string'),
    StructureProperty(name: 'test', type: 'string'),
    StructureProperty(name: 'time', type: 'string'),
    ]
class Credentials extends DataObject
{
}