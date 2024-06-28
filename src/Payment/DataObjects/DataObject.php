<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\DataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Structure\Validator;

abstract class DataObject implements DataObjectInterface, \ArrayAccess, \Countable
{
    protected array $data = [];
    public function __construct(array $data = [])
    {
        foreach($data as $key => $value) {
            if(is_array($value)) {
                $className = __NAMESPACE__ . $key;
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
                    $className = __NAMESPACE__ . $key;
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
                                'boolstr' => $data[$definition['name']] ? 'true' : 'false',
                                'boolnum' => $data[$definition['name']] ? '1' : '0',
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