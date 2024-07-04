<?php

namespace Qvickly\Api\Payment\ResponseDataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Structure\StructureClass;
use Qvickly\Api\Structure\Validator;

use function Qvickly\Api\Payment\Helpers\getBoolValue;

#[
    StructureClass(function: 'getAddress',      class: 'Address'),
    StructureClass(function: 'addPayment',      class: 'Payment'),
    StructureClass(function: 'updatePayment',   class: 'Payment'),
    StructureClass(function: 'activatePayment', class: 'Payment'),
    StructureClass(function: 'creditPayment',   class: 'Payment'),
    StructureClass(function: 'cancelPayment',   class: 'Payment'),
]
abstract class DataObject implements DataObjectInterface, \ArrayAccess, \Countable
{
    protected string $function = '';
    protected array $data = [];
    public readonly bool $isError;

    public function __construct(\stdClass|array $data = [], bool $isError = false)
    {
        $this->isError = $isError;

        if($data instanceof \stdClass) {
            $data = (array) $data;
        }
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $className = __NAMESPACE__ . "\\" . $key;
                if(class_exists($className)) {
                    $this->data[$key] = new $className($value);
                } else {
                    $this->data[$key] = new Data($value);
                }
            } else {
                $this->data[$key] = $value;
            }
        }
    }

    public static function Parse($data, $function = ''): DataObjectInterface
    {
        // First of all, we check if this is an error response
        if((is_array($data) || $data instanceof \stdClass) && array_key_exists('code', (array)$data) && array_key_exists('message', (array)$data)) {
            $class = __NAMESPACE__ . "\\" . 'Error';
            return new $class($data);
        }
        // Check if we have credentials and data in the response
        if((is_array($data) || $data instanceof \stdClass) && array_key_exists('credentials', (array)$data) && array_key_exists('data', (array)$data)) {
            $class = __NAMESPACE__ . "\\" . 'Payload';
            return $class::Parse($data);
        }
        // If not, we check if this is a registered function that might help us along the way
        $class = __NAMESPACE__ . "\\" . 'Data';
        if($function !== '') {
            $reflectionClass = new \ReflectionClass(__CLASS__);
            $attributes = $reflectionClass->getAttributes();
            if(is_array($attributes)) {
                foreach ($attributes as $attribute) {
                    $definition = $attribute->getArguments();
                    if(array_key_exists('class', $definition) && array_key_exists('function', $definition)) {
                        if($definition['function'] === $function) {
                            $class = __NAMESPACE__ . "\\" . $definition['class'];
                            break;
                        }
                    }
                }
            }
        }
        if(is_array($data) && count($data) > 0) {
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    $className = __NAMESPACE__ . "\\" . $key;
                    if (class_exists($className)) {
                        $data[$key] = new $className($value);
                    } else {
                        $data[$key] = new Data($value);
                    }
                }
            }
        }
        return new $class($data);
    }
    public function __get(string|int $name)
    {
        $data = $this->data;
        while(strpos($name, ':') > 0) {
            $parts = explode(':', $name);
            $activeName = array_shift($parts);
            $name = implode(':', $parts);
            $data = $data[$activeName] ?? null;
            if($data instanceof DataObject) {
                return $data->{$name};
            }
        }
        return $data[$name] ?? null;
    }

    public function __set(string|int|null $name, $value): void
    {
        if(is_array($value) && count($value) > 0) {
            foreach ($value as $key => $val) {
                if (is_array($val)) {
                    $className = __NAMESPACE__ . "\\" .  $key;
                    if(class_exists($className)) {
                        $value[$key] = new $className($value);
                    } else {
                        $value[$key] = new Data($value);
                    }
                }
            }
        }
        if($name === null) {
            $this->data[] = $value;
        } else {
            $this->data[$name] = $value;
        }
    }

    public function validate(array|null $data = null): bool
    {
        $validator = new Validator();
        if($data !== null) {
            return $validator->validate($data, static::class);
        }
        return $validator->validate($this->data, static::class);
    }

    public function setDefaultValues(): void
    {
        $validator = new Validator();
        $validator->setDefaultValues($this->data, static::class);
    }

    public function export(bool $convertToExportFormat = false): array|string
    {
        return $this->subExport($this->data, $convertToExportFormat);
    }

    protected function subExport(mixed $data, bool $convertToExportFormat): mixed
    {
        if(is_array($data) && count($data) > 0) {
            if($convertToExportFormat) {
                $reflectionClass = new \ReflectionClass(static::class);
                $attributes = $reflectionClass->getAttributes();
                if(is_array($attributes)) {
                    foreach ($attributes as $attribute) {
                        $definition = $attribute->getArguments();
                        if(array_key_exists('exportAs', $definition) && array_key_exists($definition['name'], $data)) {
                            $data[$definition['name']] = match($definition['exportAs']) {
                                'string' => (string) $data[$definition['name']],
                                'int' => (int) $data[$definition['name']],
                                'float' => (float) $data[$definition['name']],
                                'bool' => (bool) $data[$definition['name']],
                                'boolstr' => getBoolValue($data[$definition['name']]) ? 'true' : 'false',
                                'boolnum' => getBoolValue($data[$definition['name']]) ? '1' : '0',
                            };
                        }
                    }
                }
            }
            $export = [];
            foreach ($data as $key => $value) {
                if($value instanceof DataObject) {
                    $export[$key] = $value->export($convertToExportFormat);
                } else {
                    $export[$key] = $this->subExport($value, $convertToExportFormat);
                }
            }
            return $export;
        }
        return $data;
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->data[$offset]);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->__get($offset);
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->__set($offset, $value);
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->data[$offset]);
    }

    public function __isset(string $name): bool
    {
        return isset($this->data[$name]);
    }

    public function __unset(string $name): void
    {
        unset($this->data[$name]);
    }

    public function count(): int
    {
        return count($this->data);
    }
}