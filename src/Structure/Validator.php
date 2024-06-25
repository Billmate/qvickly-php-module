<?php
declare(strict_types=1);

namespace Qvickly\Api\Structure;

class Validator
{


    public function __construct()
    {

    }

    public function validate($data, $class): bool
    {
        $reflectionClass = new \ReflectionClass($class);
        $attributes = $reflectionClass->getAttributes();
        $validates = true;
        if(is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $definition = $attribute->getArguments();
                if(array_key_exists($definition['name'], $data)) {

                } elseif ($definition['required']) {
                    echo "Missing required attribute: {$definition['name']}\n";
                    $validates = false;
                }
            }
        }
        return $validates;
    }

    public function setDefaultValues(&$data, $class):void
    {
        $reflectionClass = new \ReflectionClass($class);
        $attributes = $reflectionClass->getAttributes();
        if(is_array($attributes)) {
            foreach ($attributes as $attribute) {
                $definition = $attribute->getArguments();
                if(array_key_exists('default', $definition) && !array_key_exists($definition['name'], $data)) {
                    $data[$definition['name']] = $definition['default'];
                }
            }
        }
    }
}