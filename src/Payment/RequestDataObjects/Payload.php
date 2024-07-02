<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\RequestDataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Structure\StructureProperty;
use Qvickly\Api\Structure\Validator;

#[
    StructureProperty(name: 'credentials', type: 'Credentials', required: true),
    StructureProperty(name: 'data',        type: 'Data'       , required: true),
    StructureProperty(name: 'function',    type: 'string'     , required: true),
]
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

    public function export(bool $convertToExportFormat = false): array
    {
        return [
            'credentials' => $this->payload['credentials']?->export($convertToExportFormat) ?? '',
            'data' => $this->payload['data']?->export($convertToExportFormat) ?? '',
            'function' => $this->function ?? ''
        ];
    }

    public function validate(): bool
    {
        $validator = new Validator();
        return $validator->validate($this->payload, $this::class);
    }
}