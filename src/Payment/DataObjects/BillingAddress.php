<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

#[
    StructureProperty(['name'=>'method', 'type'=>'int', 'required'=>false, 'default' => 0]),
    StructureProperty(['name'=>'currency', 'type'=>'string', 'required'=>false, 'default' => 'SEK']),
    StructureProperty(['name'=>'language', 'type'=>'string', 'required'=>false, 'default' => 'sv']),
    StructureProperty(['name'=>'country', 'type'=>'string', 'required'=>false, 'default' => 'SE']),
    StructureProperty(['name'=>'orderid', 'type'=>'string', 'required'=>false, 'default' => '']),
    StructureProperty(['name'=>'bankid', 'type'=>'bool', 'required'=>false, 'default' => false]),
    StructureProperty(['name'=>'accepturl', 'type'=>'string', 'required'=>false, 'default' => '']),
    StructureProperty(['name'=>'cancelurl', 'type'=>'string', 'required'=>false, 'default' => '']),
    StructureProperty(['name'=>'callbackurl', 'type'=>'string', 'required'=>false, 'default' => '']),
    StructureProperty(['name'=>'autocancel', 'type'=>'int', 'required'=>false, 'default' => 0]),
]
class BillingAddress extends DataObject
{
}