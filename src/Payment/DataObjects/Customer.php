<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;


class Customer extends DataObject
{
    protected BillingAddress $billingAddress;
    protected ShippingAddress $shippingAddress;

    public function __construct(array|null $data)
    {
        if(is_array($data)) {
            if($data['Billing']) {
                if(is_array($data['Billing'])) {
                    $this->billingAddress = new BillingAddress($data['Billing']);
                } elseif($data['Billing'] instanceof BillingAddress) {
                    $this->billingAddress = $data['Billing'];
                }
                unset($data['billing']);
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

    public function export(): array
    {
        $export = parent::export();
        if(isset($this->billingAddress) && $this->billingAddress instanceof BillingAddress){
            $export['Billing'] = $this->billingAddress->export();
        }
        if (isset($this->shippingAddress) && $this->shippingAddress instanceof ShippingAddress) {
            $export['Shipping'] = $this->shippingAddress->export();
        }
        return $export;
    }
}