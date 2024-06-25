<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Structure\Structure;
use Qvickly\Api\Structure\Validator;

class DataObject implements DataObjectInterface
{
    public function __construct(protected array $data = [])
    {
    }

    public function __get(string $name)
    {
        return $this->data[$name] ?? null;
    }

    public function __set(string $name, $value): void
    {
        $this->data[$name] = $value;
    }

    public function validate(): bool
    {
        $validator = new Validator();
        return $validator->validate($this->data, static::class);
    }

    public function setDefaultValues(): void
    {
        $validator = new Validator();
        $validator->setDefaultValues($this->data, static::class);
    }
    public function export(): array|string
    {
        return $this->data;
    }

}