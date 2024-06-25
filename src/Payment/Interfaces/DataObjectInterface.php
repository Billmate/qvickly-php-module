<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\Interfaces;

interface DataObjectInterface
{
    public function export(): array|string;
}