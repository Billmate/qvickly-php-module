<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\ResponseDataObjects;


use Qvickly\Api\Payment\RequestDataObjects\DataObject;
use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'hash',     type: 'string', required: true),
    ]
class Credentials extends DataObject
{
}