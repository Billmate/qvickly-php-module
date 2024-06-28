<?php

namespace Qvickly\Api\Payment\Exception;

class PaymentAPIException extends \Exception
{

    /**
     * @param string $string
     */
    public function __construct(string $string)
    {
        parent::__construct($string);
    }
}