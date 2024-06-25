<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

#[
    StructureProperty(name: 'method', type: 'int'),
    StructureProperty(name: 'country', type: 'string'),
    StructureProperty(name: 'language', type: 'string'),
    StructureProperty(name: 'orderid', type: 'string'),
    StructureProperty(name: 'currency', type: 'string'),
    StructureProperty(name: 'autoactivate', type: 'string'),
    StructureProperty(name: 'autocancel', type: 'int'),
    StructureProperty(name: 'logo', type: 'string'),
    StructureProperty(name: 'paymentplanid', type: 'int'),
    StructureProperty(name: 'accepturl', type: 'string'),
    StructureProperty(name: 'cancelurl', type: 'string'),
    StructureProperty(name: 'callbackurl', type: 'string'),
    StructureProperty(name: 'bankid', type: 'boolstring'),
    ]
class PaymentData extends DataObject
{
    public function __construct(array|null $data = [])
    {
        parent::__construct($data);
    }

    public function export(): array|string
    {
        // TODO: Implement export() method.
        return [];
    }
}