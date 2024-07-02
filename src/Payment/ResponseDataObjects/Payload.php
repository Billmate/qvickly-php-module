<?php

namespace Qvickly\Api\Payment\ResponseDataObjects;

use Qvickly\Api\Payment\Interfaces\DataObjectInterface;

class Payload implements DataObjectInterface
{
    private ?Credentials $credentials = null;
    private ?DataObject $dataObject = null;
    private array $data = [];

    public function __construct()
    {
    }

    public function __get(string $name)
    {
        if($name === 'credentials') {
            return $this->credentials;
        }
        if($name === 'data') {
            return $this->dataObject;
        }
        return $this->data[$name];
    }
    public function __set(string $name, $value): void
    {
        if($name === 'credentials') {
            $this->credentials = $value;
        } elseif($name === 'data') {
            $this->dataObject = $value;
        } elseif($name === null) {
            $this->data[] = $value;
        } else {
            $this->data[$name] = $value;
        }
    }

    public static function Parse($data, $function = '')
    {
        if($data instanceof \stdClass) {
            $data = (array) $data;
        }
        $payload = new Payload();
        if(array_key_exists('credentials', $data)) {
            $payload->credentials = new Credentials($data['credentials']);
        }
        if(array_key_exists('data', $data)) {
            $payload->dataObject = DataObject::Parse($data['data'], $function);
        }
        return $payload;
    }

    public function export(bool $convertToExportFormat = false): array|string
    {
        $data = [];
        foreach($this->data as $key => $value) {
            if($value instanceof DataObjectInterface) {
                $data[$key] = $value->export($convertToExportFormat);
            } else if(is_array($value)) {
                $data[$key] = [];
                foreach($value as $k => $v) {
                    if($v instanceof DataObjectInterface) {
                        $data[$key][$k] = $v->export($convertToExportFormat);
                    } else {
                        $data[$key][$k] = $v;
                    }
                }
            } else {
                $data[$key] = $value;
            }
        }
        if(isset($this->credentials)) {
            $data['credentials'] = $this->credentials->export($convertToExportFormat);
        }
        if(isset($this->dataObject)) {
            $data['data'] = $this->dataObject->export($convertToExportFormat);
        }
        return $data;
    }


    public function validate(): bool
    {
        $validates = true;
        if(isset($this->credentials)) {
            $data['credentials'] = $this->credentials->export($convertToExportFormat);
        }
        if(isset($this->data)) {
            $data['data'] = $this->data->export($convertToExportFormat);
        }
        return $data;

    }
}