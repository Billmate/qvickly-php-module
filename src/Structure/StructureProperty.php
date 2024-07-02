<?php

namespace Qvickly\Api\Structure;

#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_CLASS)]
class StructureProperty
{
    /**
     * @param string $name
     * @param string $type
     * @param bool $required
     * @param string|null $default
     * @param string|null $exportAs
     * @param string|null $precision
     * @param array $values
     */
    public function __construct(
        private string $name,
        private string $type,
        private bool $required = false,
        private string|null $default = null,
        private string|null $exportAs = null,
        private string|null $precision = null,
        private array $values = []
    )
    {
    }
}