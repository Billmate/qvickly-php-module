<?php

namespace Qvickly\Api\Payment\DataObjects;

class Articles extends DataObject
{

    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->data[] = new Article($value);
            }
        }
    }
}