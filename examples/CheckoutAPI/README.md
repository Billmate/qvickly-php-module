# Qvickly Checkout API - Examples

These examples are meant to show how the Qvickly Checkout API works.

The examples are not meant to be used in production.

The examples are not focused on a specific function, but rather on the flow of the API.

## Examples

### Get the checkout version

[getCheckoutVersion](0-getLatestVersion.php)

Send a request to the API to get the version at the server (which is the latest version).

### Create a checkout
    
[createCheckout](1-createCheckout.php)

Create a checkout.

### Set personal information

[setPersonalInformation](2-setPersonalInformation.php)

Create a checkout and set the personal information needed to pass the first step of the checkout.

### Update shipping address

[updateShippingAddress](2a-updateShippingAddress.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then update the shipping address.

### Update billing address

[updateBillingAddress](2b-updateBillingAddress.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then update the billing address.

### Get payment plans

[getpaymentplans](3-getpaymentplans.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans.

### Get payment methods

[getPaymentMethods](4-getPaymentMethods.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans and all payment methods.

### Set payment methods

[setPaymentMethod](5-setPaymentMethod.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans and all payment methods. After that, set the payment method to use.

### Get payment

[get](6-get.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans and all payment methods. After that, set the payment method to use.

Then get the payment from the server.

### Validate

[validate](7-validate.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans and all payment methods. After that, set the payment method to use.

Then get the payment from the server and finally send a validate call to the server.

### Confirm

[confirm](8-confirm.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then fetch all payment plans and all payment methods. After that, set the payment method to use.

Then get the payment from the server and finally send a confirm call to the server.

### Reset

[reset](9-reset.php)

Create a checkout and set the personal information needed to pass the first step of the checkout. Then send a reset call to the server.
