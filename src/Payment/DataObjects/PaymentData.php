<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;


#[
    StructureProperty(name: 'method',        type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'country',       type: 'string'),
    StructureProperty(name: 'language',      type: 'string'),
    StructureProperty(name: 'orderid',       type: 'string'),
    StructureProperty(name: 'currency',      type: 'string'),
    StructureProperty(name: 'autoactivate',  type: 'string'),
    StructureProperty(name: 'autocancel',    type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'logo',          type: 'string'),
    StructureProperty(name: 'paymentplanid', type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'accepturl',     type: 'string'),
    StructureProperty(name: 'cancelurl',     type: 'string'),
    StructureProperty(name: 'callbackurl',   type: 'string'),
    StructureProperty(name: 'bankid',        type: 'boolstr'),
    ]
class PaymentData extends DataObject
{
    public function __construct(array|null $data = [])
    {
        parent::__construct($data);
    }

}