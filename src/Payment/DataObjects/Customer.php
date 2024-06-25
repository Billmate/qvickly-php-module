<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

class Customer extends DataObject
{
    protected BillingAddress $billingAddress;
    protected ShippingAddress $shippingAddress;

    public function __construct(array|null $data)
    {
        if(is_array($data)) {
            if($data['Billing']) {
                $this->billingAddress = new BillingAddress($data['Billing']);
                unset($data['billing']);
            }
            if($data['Shipping']) {
                $this->shippingAddress = new ShippingAddress($data['Shipping']);
                unset($data['shipping']);
            }
        }
        parent::__construct($data);
    }

    public function export(): array
    {
        $export = parent::export();
        if($this->billingAddress instanceof BillingAddress){
            $export['Billing'] = $this->billingAddress->export();
        }
        if ($this->shippingAddress instanceof ShippingAddress) {
            $export['Shipping'] = $this->shippingAddress->export();
        }
        return $export;
    }
}