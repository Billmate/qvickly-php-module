<?php

namespace Qvickly\Api\Structure;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class StructureClass
{
    /**
     * @param string $function
     * @param string $class
     */
    public function __construct(
        private string $function,
        private string $class
    )
    {
    }
}