<?php
declare(strict_types=1);

namespace Qvickly\Api\Structure;

class Structure
{
    protected array $definitions = [];

    public function __construct(array|null $definitions = null)
    {
        if ($definitions) {
            $this->definitions = $definitions;
        }
    }

    public function add(array $definition)
    {
        $this->definitions[] = $definition;
    }

    public function addMultiple(array $definitions)
    {
        foreach ($definitions as $definition) {
            $this->add($definition);
        }
    }

    public function loop()
    {
        foreach ($this->definitions as $definition) {
            yield $definition;
        }
    }

}
