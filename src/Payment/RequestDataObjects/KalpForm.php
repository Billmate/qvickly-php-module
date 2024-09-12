<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\RequestDataObjects;


use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'monthlyIncome',        type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'nbrOfPerson',          type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'typeOfAccommodation',  type: 'string'),
    StructureProperty(name: 'monthlyExpenses',      type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'monthlyLoans',         type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'monthlyCost',          type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'result',               type: 'int',   exportAs: 'string'),
    StructureProperty(name: 'date',                 type: 'string'),
    StructureProperty(name: 'status',               type: 'string'),
    ]
class KalpForm extends DataObject
{
    public function __construct(array|null $data = [])
    {
        parent::__construct($data);
    }

}