<?php

namespace Qvickly\Api\Enums;

enum ReturnDataType: string
{
    case PROCESSED = 'processed';
    case RAW = 'raw';
    case OBJECT = 'object';
    case EXPORT = 'export';
}
