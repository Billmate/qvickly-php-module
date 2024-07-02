<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\RequestDataObjects;

use Qvickly\Api\Structure\StructureProperty;

#[
    StructureProperty(name: 'nr',  type: 'int',    exportAs: 'string'),
    StructureProperty(name: 'pno', type: 'string', exportAs: 'string'),
]
class Customer extends DataObject
{
    protected BillingAddress $billingAddress;
    protected ShippingAddress $shippingAddress;

    public function __construct(array $data = [])
    {
        if(is_array($data)) {
            if($data['Billing']) {
                if(is_array($data['Billing'])) {
                    $this->billingAddress = new BillingAddress($data['Billing']);
                } elseif($data['Billing'] instanceof BillingAddress) {
                    $this->billingAddress = $data['Billing'];
                }
                unset($data['Billing']);
            }
            if($data['Shipping']) {
                if(is_array($data['Shipping'])) {
                    $this->shippingAddress = new ShippingAddress($data['Shipping']);
                } elseif($data['Shipping'] instanceof ShippingAddress) {
                    $this->shippingAddress = $data['Shipping'];
                }
                unset($data['Shipping']);
            }
        }
        parent::__construct($data);
    }

    public function __get(int|string $name)
    {
        if($name === 'Billing') {
            return $this->billingAddress;
        } elseif($name === 'Shipping') {
            return $this->shippingAddress;
        }
        return parent::__get($name);
    }

    public function export(bool $convertToExportFormat = false): array
    {
        $export = parent::export($convertToExportFormat);
        if(isset($this->billingAddress) && $this->billingAddress instanceof BillingAddress){
            $export['Billing'] = $this->billingAddress->export($convertToExportFormat);
        }
        if (isset($this->shippingAddress) && $this->shippingAddress instanceof ShippingAddress) {
            $export['Shipping'] = $this->shippingAddress->export($convertToExportFormat);
        }
        return $export;
    }
}