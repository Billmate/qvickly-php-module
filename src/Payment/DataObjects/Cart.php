<?php

namespace Qvickly\Api\Payment\DataObjects;

class Cart extends DataObject
{
    protected CartHandling $handling;
    protected CartShipping $shipping;
    protected CartTotal $total;

    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $key => $value) {
            if ($key === 'handling') {
                if(is_array($value)) {
                    $value = new CartHandling($value);
                }
                $this->handling = $value;
            }
            if ($key === 'shipping') {
                if(is_array($value)) {
                    $value = new CartShipping($value);
                }
                $this->shipping = $value;
            }
            if ($key === 'total') {
                if(is_array($value)) {
                    $value = new CartTotal($value);
                }
                $this->total = $value;
            }
        }
    }

    public function export(): array|string
    {
        $export = [];
        if(isset($this->handling)) {
            $export['handling'] = $this->handling->export();
        }
        if(isset($this->shipping)) {
            $export['shipping'] = $this->shipping->export();
        }
        if(isset($this->total)) {
            $export['total'] = $this->total->export();
        }
        return $export;
    }
}