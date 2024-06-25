<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;


class Data extends DataObject
{
    public function __construct(array|null $data = null)
    {
        parent::__construct();
        if(is_array($data)) {
            foreach ($data as $key => $value) {
                if(is_array($value)) {
                    $this->data[$key] = match($key) {
                        'shipping_address' => new ShippingAddress($value),
                        default => $value
                    };
                } else {
                    $this->data[$key] = $value;
                }
            }
        }
    }

    public function hash(string $secret)
    {
        return hash_hmac('sha512', json_encode($this->export()), $secret);
    }
}