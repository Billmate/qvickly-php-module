# Qvickly Payment API

```php
<?php
declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\RequestDataObjects\PaymentData;

$paymentAPI = new PaymentAPI($_ENV['EID'], $_ENV['SECRET']);
$data = new Data(
    [
        "PaymentData" => new PaymentData([
            "comingFromPF2" => "1"
        ])
    ]
);
$payment = $paymentAPI->getAccountInfo($data);
```