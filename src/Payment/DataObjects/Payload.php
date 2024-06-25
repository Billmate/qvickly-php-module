<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

class Payload implements DataObjectInterface
{
    private Credentials $credentials;
    private string $function;
    private Data $data;

    public function export(): array
    {
        return [
            'credentials' => $this->credentials->export(),
            'data' => $this->data->export(),
            'function' => $this->function
        ];
    }
}