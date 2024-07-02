<?php

namespace Qvickly\Api\Structure;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class StructureProperty
{
    public function __construct(string $name, string $type, bool $required = false, string $default = null, string $exportAs = null, string $precision = null, array $values = [])
    {
    }
}