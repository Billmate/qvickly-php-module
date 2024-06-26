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

    public function __get(int|string $name)
    {
        if($name === 'Handling') {
            return $this->handling;
        } elseif($name === 'Shipping') {
            return $this->shipping;
        } elseif($name === 'total') {
            return $this->total;
        }
        return parent::__get($name);
    }

    public function updateTotals(int $withouttax, int $tax, int $withtax, int|null $rounding = null): void
    {
        if(!isset($this->total)) {
            $this->total = new CartTotal();
        }
        $this->total->withouttax = 0;
        $this->total->tax = 0;
        $this->total->withtax = 0;
        $this->total->rounding = 0;

        if(isset($this->handling)) {
            $this->total->withouttax += $this->handling->withouttax;
            $this->total->tax += ($this->handling->withouttax * $this->handling->taxrate / 100);
            $this->total->withtax += ($this->handling->withouttax * ($this->handling->taxrate + 100) / 100);
        }
        if(isset($this->shipping)) {
            $this->total->withouttax += $this->shipping->withouttax;
            $this->total->tax += ($this->shipping->withouttax * $this->shipping->taxrate / 100);
            $this->total->withtax += ($this->shipping->withouttax * ($this->shipping->taxrate + 100) / 100);
        }
        $this->total->withouttax += $withouttax;
        $this->total->tax += $tax;
        $this->total->withtax += $withtax;
        if($rounding !== null) {
            $this->total->rounding += $rounding;
        }
    }

    public function export(): array|string
    {
        $export = [];
        if(isset($this->handling)) {
            $export['Handling'] = $this->handling->export();
        }
        if(isset($this->shipping)) {
            $export['Shipping'] = $this->shipping->export();
        }
        if(isset($this->total)) {
            $export['total'] = $this->total->export();
        }
        return $export;
    }
}