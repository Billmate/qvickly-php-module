<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\ResponseDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'code',    type: 'string', exportAs: 'string'),
    StructureProperty(name: 'message', type: 'string'),
    StructureProperty(name: 'logid',   type: 'string')
]
class Error extends DataObject
{
    public function __construct(array|\stdClass $data = [])
    {
        parent::__construct($data, true);
    }
}

/*
(
    [code] => 5201
    [message] => Faktura med nummer 12345 som du efterfrågade existerar inte.
    [logid] => 5747842
)
 */