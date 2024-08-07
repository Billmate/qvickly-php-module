<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\RequestDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'email',     type: 'string', required: true),
    StructureProperty(name: 'firstname', type: 'string'                ),
    StructureProperty(name: 'lastname',  type: 'string'                ),
    StructureProperty(name: 'company',   type: 'string'                ),
    StructureProperty(name: 'zip',       type: 'string'                ),
    StructureProperty(name: 'city',      type: 'string'                ),
    StructureProperty(name: 'phone',     type: 'string'                ),
    StructureProperty(name: 'country',   type: 'string'                ),
    StructureProperty(name: 'street',    type: 'string'                ),
    StructureProperty(name: 'street2',   type: 'string'                ),
]
class BillingAddress extends DataObject
{
}