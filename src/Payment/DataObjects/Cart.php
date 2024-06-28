<?php

namespace Qvickly\Api\Payment\DataObjects;

#[
    StructureProperty(name: 'Total', required: true)
]
class Cart extends DataObject
{
    protected CartHandling $handling;
    protected CartShipping $shipping;
    protected CartTotal $Total;

    public function __construct(array $data = [])
    {
        parent::__construct();
        foreach ($data as $key => $value) {
            $lcKey = strtolower($key);
            if ($lcKey === 'handling') {
                if(is_array($value)) {
                    $value = new CartHandling($value);
                }
                $this->handling = $value;
            }
            if ($lcKey === 'shipping') {
                if(is_array($value)) {
                    $value = new CartShipping($value);
                }
                $this->shipping = $value;
            }
            if ($lcKey === 'total') {
                if(is_array($value)) {
                    $value = new CartTotal($value);
                }
                $this->Total = $value;
            }
        }
    }

    public function __get(int|string $name)
    {
        if($name === 'Handling') {
            return $this->handling;
        } elseif($name === 'Shipping') {
            return $this->shipping;
        } elseif($name === 'Total') {
            return $this->Total;
        }
        return parent::__get($name);
    }

    public function validate(array|null $data = null): bool
    {
        $validateData = $this->data;
        if(is_array($data)) {
            $validateData = array_merge_recursive($validateData, $data);
        }
        if(isset($this->handling)) {
            $validateData['Handling'] = $this->handling;
        }
        if(isset($this->shipping)) {
            $validateData['Shipping'] = $this->shipping;
        }
        if(isset($this->Total)) {
            $validateData['Total'] = $this->Total;
        }
        return parent::validate($validateData);
    }

    public function updateTotals(int $withouttax, int $tax, int $withtax, int|null|bool $rounding = null): void
    {
        if(!isset($this->Total)) {
            $this->Total = new CartTotal();
        }
        $this->Total->withouttax = 0;
        $this->Total->tax = 0;
        $this->Total->withtax = 0;
        $this->Total->rounding = 0;

        if(isset($this->handling)) {
            $this->Total->withouttax += $this->handling->withouttax;
            $this->Total->tax += ($this->handling->withouttax * $this->handling->taxrate / 100);
            $this->Total->withtax += ($this->handling->withouttax * ($this->handling->taxrate + 100) / 100);
        }
        if(isset($this->shipping)) {
            $this->Total->withouttax += $this->shipping->withouttax;
            $this->Total->tax += ($this->shipping->withouttax * $this->shipping->taxrate / 100);
            $this->Total->withtax += ($this->shipping->withouttax * ($this->shipping->taxrate + 100) / 100);
        }
        $this->Total->withouttax += $withouttax;
        $this->Total->tax += $tax;
        $this->Total->withtax += $withtax;
        if($rounding === true) {
            $this->Total->withtax = (int)(round($this->Total->withtax / 100 + 0.01, 0) * 100);
            $this->Total->rounding = $this->Total->withtax - $this->Total->tax - $this->Total->withouttax;
        } elseif($rounding === false) {
            $this->Total->rounding = 0;
        } elseif($rounding !== null) {
            $this->Total->rounding += $rounding;
            $this->Total->withtax += $rounding;
        }
    }

    public function export(bool $convertToExportFormat = false): array|string
    {
        $export = [];
        if(isset($this->handling)) {
            $export['Handling'] = $this->handling->export($convertToExportFormat);
        }
        if(isset($this->shipping)) {
            $export['Shipping'] = $this->shipping->export($convertToExportFormat);
        }
        if(isset($this->Total)) {
            $export['Total'] = $this->Total->export($convertToExportFormat);
        }
        return $export;
    }
}