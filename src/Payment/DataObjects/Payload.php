<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

class Payload implements DataObjectInterface
{
    private array $payload = [];

    public function __construct(array|null $payload = null)
    {
        if ($payload) {
            if(array_key_exists('credentials', $payload)) {
                if(is_array($payload['credentials'])) {
                    $this->payload['credentials'] = new Credentials($payload['credentials']);
                } elseif($payload['credentials'] instanceof Credentials) {
                    $this->payload['credentials'] = $payload['credentials'];
                }
            }
            if(array_key_exists('data', $payload)) {
                if(is_array($payload['data'])) {
                    $this->payload['data'] = new Data($payload['data']);
                } elseif($payload['data'] instanceof Data) {
                    $this->payload['data'] = $payload['data'];
                }
            }
            if(array_key_exists('function', $payload)) {
                $this->payload['function'] = $payload['function'];
            }
        }
    }

    public function __get(string $name)
    {
        return match($name) {
            'credentials' => $this->payload['credentials'],
            'data' => $this->payload['data'],
            'function' => $this->payload['function'],
            default => null
        };
    }

    public function export(): array
    {
        return [
            'credentials' => $this->payload['credentials']?->export() ?? '',
            'data' => $this->payload['data']?->export() ?? '',
            'function' => $this->function ?? ''
        ];
    }
}