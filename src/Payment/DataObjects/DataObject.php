<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
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
        return $this->subExport($this->data);
    }

    protected function subExport(mixed $data): mixed
    {
        if(is_array($data) && count($data) > 0) {
            $export = [];
            foreach ($data as $key => $value) {
                if($value instanceof DataObject) {
                    $export[$key] = $value->export();
                } else {
                    $export[$key] = $this->subExport($value);
                }
            }
            return $export;
        }
        return $data;
    }
}