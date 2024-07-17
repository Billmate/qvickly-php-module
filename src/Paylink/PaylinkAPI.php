<?php
declare(strict_types=1);

namespace Qvickly\Api\Paylink;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\PaymentAPI;
use stdClass;

class PaylinkAPI
{
    protected PaymentAPI $paymentAPI;

    public function __construct(
        private readonly string $eid,
        private readonly string $secret,
        private readonly bool $testMode = false,
        private array $overrides = []
    )
    {
        $this->paymentAPI = new PaymentAPI(eid: $this->eid, secret: $this->secret, testMode: $this->testMode, overrides: $this->overrides);
    }

    public function __call(string $name, array $arguments)
    {
        return match($name) {
            'create' => call_user_func_array(array($this, 'create'), $arguments),
            default => throw new \Exception('Method not found')
        };
    }

    public static function __callStatic(string $name, array $arguments)
    {
        $eid = defined('QVICKLY_PAYLINK_API_EID') ? constant('QVICKLY_PAYLINK_API_EID') : $_ENV['EID'];
        $secret = defined('QVICKLY_PAYLINK_API_SECRET') ? constant('QVICKLY_PAYLINK_API_SECRET') : $_ENV['SECRET'];
        if(!isset($eid) || !isset($secret)) {
            throw new \Exception('EID and SECRET must be set either as constants or in the environment variables');
        }
        return match($name) {
            'create' => call_user_func_array(array(new self($eid, $secret), 'create'), $arguments),
            default => throw new \Exception('Method not found')
        };
    }

    protected function create(array|Data $data, bool|null|int $roundCart = null): array|string|DataObjectInterface|stdClass
    {
        if(is_array($data)) {
            $data = new Data($data);
        }
        $data->updateCart($roundCart);
        return $this->paymentAPI->addPayment($data);
    }

}