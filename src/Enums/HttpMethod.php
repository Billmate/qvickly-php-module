<?php
declare(strict_types=1);

namespace Qvickly\Api\Enums;

enum HttpMethod: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';
    case PATCH = 'PATCH';
    case OPTIONS = 'OPTIONS';
    case HEAD = 'HEAD';
    case TRACE = 'TRACE';
    case CONNECT = 'CONNECT';
}