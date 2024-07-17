# Qvickly Paylink API

> The Paylink API is more of an implemented services that uses the Payment API to create a payment link.

## Installation

```bash
composer require qvickly/api
```

## Usage

```php
<?php
use Qvickly\Api\Paylink\PaylinkAPI;

$paylinkAPI = new PaylinkAPI($eid, $secret);
$data = [];
// Build data array
$payment = $paylinkAPI->create($data);

// Redirect user to $payment['url'] if it is set
```
This code does the same thing as the following code:
```php
<?php
use Qvickly\Api\Payment\PaymentAPI;

$paymentAPI = new PaymentAPI($eid, $secret);
$data = new Data([]);
// Build data array
$data->updateCart();
$payment = $paymentAPI->addPayment($data);

// Redirect user to $payment['url'] if it is set
```

## Functions

### create($data, $roundCart = null)
This function takes the data, which can be either an array or a Data object, and creates a payment link. Before the addPayment function is called, the data is updated with the cart, so there is no need to calculate the cart total.

The second parameter is optional and is used to round the cart total. If the cart total is 100.50 and the roundCart parameter is set to 1, the cart total will be 101.00. This is used by adding a rounding attribute to the total.

## Important parameters

### autocancel

Paylinks will by default have 2880 minutes (48 hours) expiration time. If you want to change this, you can set the `autocancel` parameter to the number of minutes you want the link to be active.



## QR Code

To create a QR code, just use the `url` property from the response array and create a QR code from that URL.
