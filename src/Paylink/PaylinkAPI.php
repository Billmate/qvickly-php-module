<?php
declare(strict_types=1);

namespace Qvickly\Api\Paylink;

use Qvickly\Api\Payment\DataObjects\Data;
use Qvickly\Api\Payment\PaymentAPI;
use stdClass;

class PaylinkAPI
{
    protected PaymentAPI $paymentAPI;

    public function __construct(private readonly string $eid, private readonly string $secret, private readonly bool $testMode = false)
    {
        $this->paymentAPI = new PaymentAPI($this->eid, $this->secret, $this->testMode);
    }

    public function create(array $data): string|array|stdClass
    {
        $data = new Data($data);
        return $this->paymentAPI->addPayment($data);
    }

}