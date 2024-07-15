<?php

namespace Qvickly\Api\Enums;

enum PaymentMethod: int
{
    case FACTORING = 1;
    case HANDLING = 2;
    case PARTPAYMENT = 4;
    case CARD = 8;
    case BANK = 16;
    case CASH = 32;
    case CHECKOUT = 64;
    case SIMPLE = 128;
    case PAYLINK = 256;
    case SWISH2 = 512;
    case SWISH = 1024;
    case PAYWITHQVICKLY = 2048;
}
