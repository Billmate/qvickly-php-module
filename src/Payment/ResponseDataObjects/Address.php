<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\ResponseDataObjects;


use Qvickly\Api\Payment\RequestDataObjects\DataObject;
use \Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'firstname', type: 'string'),
    StructureProperty(name: 'lastname',  type: 'string'),
    StructureProperty(name: 'company',   type: 'string'),
    StructureProperty(name: 'zip',       type: 'string'),
    StructureProperty(name: 'city',      type: 'string'),
    StructureProperty(name: 'phone',     type: 'string'),
    StructureProperty(name: 'country',   type: 'string'),
    StructureProperty(name: 'street',    type: 'string'),
    StructureProperty(name: 'street2',   type: 'string'),
    StructureProperty(name: 'type',      type: 'string', values: ['person', 'company']),
]
class Address extends DataObject
{

}