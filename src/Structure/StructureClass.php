<?php

namespace Qvickly\Api\Structure;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class StructureClass
{
    public function __construct(string $function, string $class)
    {
    }
}