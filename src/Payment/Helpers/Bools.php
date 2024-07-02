<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment\Helpers;

use Qvickly\Api\Payment\RequestDataObjects\Data;

function getBoolValue(mixed $bool): bool
{
    if (is_bool($bool)) {
        return $bool;
    }
    if (is_string($bool)) {
        return strtolower($bool) === 'true';
    }
    if (is_int($bool)) {
        return $bool !== 0;
    }
    return (bool)$bool;
}
