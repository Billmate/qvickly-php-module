<?php
declare(strict_types=1);

namespace Qvickly\Api\Paylink;

use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\PaymentAPI;
use stdClass;

class PaylinkAPI
{
    protected PaymentAPI $paymentAPI;

    public function __construct(private readonly string $eid, private readonly string $secret, private readonly bool $testMode = false)
    {
        $this->paymentAPI = new PaymentAPI($this->eid, $this->secret, $this->testMode);
    }

    public function __call(string $name, array $arguments)
    {
        match($name) {
            'create' => $this->create(...$arguments),
            default => throw new \Exception('Method not found')
        };
    }

    public static function __callStatic(string $name, array $arguments)
    {
        match($name) {
            'create' => (new self(...$arguments))->create(...$arguments),
            default => throw new \Exception('Method not found')
        };
    }

    protected function create(array|Data $data, bool|null|int $roundCart = null): string|array|stdClass
    {
        if(is_array($data)) {
            $data = new Data($data);
        }
        $data->updateCart($roundCart);
        return $this->paymentAPI->addPayment($data);
    }

}