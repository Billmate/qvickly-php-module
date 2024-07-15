<?php
declare(strict_types=1);

namespace Qvickly\Api\Checkout;

use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Cookie\SessionCookieJar;
use GuzzleHttp\Psr7\Request;
use Qvickly\Api\Enums\HttpMethod;
use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\ResponseDataObjects\Data as ResponseData;
use Qvickly\Api\Traits\RequestTraits;

if(!defined('QVICKLY_CHECKOUTAPI_BASE_URL')) {
    define('QVICKLY_CHECKOUTAPI_BASE_URL', 'https://checkout.billmate.se/');
}
if(!defined('QVICKLY_CHECKOUTAPI_CLIENT_VERSION')) {
    define('QVICKLY_CHECKOUTAPI_CLIENT_VERSION', '1.0.0');
}
if(!defined('QVICKLY_CHECKOUTAPI_SERVER_VERSION')) {
    define('QVICKLY_CHECKOUTAPI_SERVER_VERSION', '2.5.0');
}
if(!defined('QVICKLY_CHECKOUTAPI_CLIENT_NAME')) {
    define('QVICKLY_CHECKOUTAPI_CLIENT_NAME', 'Qvickly:PHP-module:Checkout-' . QVICKLY_CHECKOUTAPI_CLIENT_VERSION);
}

class CheckoutAPI
{
    use RequestTraits;
    private PaymentAPI $paymentAPI;
    private Client $client;
    private CookieJar $cookieJar;

    const QVICKLY_BASE_URL = QVICKLY_CHECKOUTAPI_BASE_URL;
    const QVICKLY_BASE_URL_KEY = 'CHECKOUT_BASE_URL';

    public function __construct(private string $eid, private string $secret, private bool $testMode = false, private array $overrides = [])
    {
        $this->overrides['CLIENT_NAME'] = $this->overrides['CLIENT_NAME'] ?? QVICKLY_CHECKOUTAPI_CLIENT_NAME;
        $this->overrides['SERVER_VERSION'] = $this->overrides['SERVER_VERSION'] ?? QVICKLY_CHECKOUTAPI_SERVER_VERSION;
        $this->paymentAPI = new PaymentAPI(eid: $eid, secret: $secret, testMode: $testMode, overrides: $this->overrides);
        $this->cookieJar = new SessionCookieJar('QvicklyCheckout');
        $this->client = new Client([
            'base_uri' => $this->overrides[static::QVICKLY_BASE_URL_KEY] ?? QVICKLY_CHECKOUTAPI_BASE_URL,
            'cookies' => true,
        ]);
    }

    private function send(string $method, string $url, string $data = '', array $headers = [], array $params = [])
    {
        $fullUrl = $this->buildUrl($url);
        $query = http_build_query($params);
        if ($query) {
            if(strpos($fullUrl, '?') === false) {
                $fullUrl .= '?';
            } else {
                $fullUrl .= '&';
            }
            $fullUrl .= $query;
        }
        $headers['Content-Length'] = strlen($data);
        $request = new Request($method, $fullUrl, $headers, $data);
        try {
            $response = $this->client->send($request, [
                'cookies' => $this->cookieJar
            ]);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }
    private function callGET(string $url)
    {
        return $this->send(HttpMethod::GET->value, $url);
    }

    private function callPOST(string $url, array $data)
    {
        if(!array_key_exists('eid', $data)) {
            $data['eid'] = $this->eid;
        }
        if($this->testMode) {
            $data['test'] = 'true';
        }

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        ];
        return $this->send(HttpMethod::POST->value, $url, $this->makePostData($data), $headers);
    }

    public function getVersion()
    {
        $payload = [
            'version' => '0.0.0'
        ];
        $version = $this->callPOST('/public/ajax.php', $payload);
        return $version['version.compare'] ?? '0.0.0';
    }

    public function initCheckout(array|Data $data): array|ResponseData
    {
        $result = $this->paymentAPI->initCheckout($data);
        if(is_array($result) && array_key_exists('url', $result)) {
            $url = parse_url($result['url']);
            $pathParts = explode('/', ltrim($url['path'], '/'));
            if(count($pathParts) > 1) {
                $result['hash'] = $pathParts[1];
            }
        }
        return $result;
    }

    public function updateCheckout(array|Data $data): array|ResponseData
    {
        return $this->paymentAPI->updateCheckout($data);
    }

    public function reset(string $hash)
    {
        $url = sprintf('/%s/%s%s/reset', $this->eid, $hash, $this->testMode ? '/test' : '');
        return $this->callGET($url);
    }

    public function isPaid(string $hash, array $data)
    {
        $data['hash'] = $data['hash'] ?? $hash;
        return $this->callPOST('/public/isPaid', $data);
    }

    public function checkoutStatus(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?checkoutStatus', $payload);
    }

    public function bankidopen(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?bankidopen', $payload);
    }

    public function bankidclose(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?bankidclose', $payload);
    }

    public function cancelInvoice(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?cancelInvoice', $payload);
    }

    public function get(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?get', $payload);
    }

    public function clearCheckout(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?clearCheckout', $payload);
    }

    public function updateStatus(string $hash, string $status)
    {
        $payload = [
            'hash' => $hash,
            'status' => $status
        ];
        return $this->callPOST('/public/ajax.php?updateStatus', $payload);
    }

    public function updateMessage(string $hash, string $message)
    {
        $payload = [
            'hash' => $hash,
            'message' => $message
        ];
        return $this->callPOST('/public/ajax.php?updateMessage', $payload);
    }

    public function updatePaymentMethod(string $hash, string|int $paymentMethod)
    {
        $payload = [
            'hash' => $hash,
            'method' => (string)$paymentMethod
        ];
        return $this->callPOST('/public/ajax.php?updatePaymentMethod', $payload);
    }

    public function updatePaymentPlan(string $hash, string|int $paymentPlanId)
    {
        $payload = [
            'hash' => $hash,
            'paymentplanid' => (string)$paymentPlanId
        ];
        return $this->callPOST('/public/ajax.php?updatePaymentPlan', $payload);
    }

    public function updateBillingAddress(string $hash, array $address)
    {
        $payload = [
            'hash' => $hash,
            'billingAddress' => $address
        ];
        return $this->callPOST('/public/ajax.php?updateBillingAddress', $payload);
    }

    public function updateShippingAddress(string $hash, array $address)
    {
        $payload = [
            'hash' => $hash,
            'shippingAddress' => $address
        ];
        return $this->callPOST('/public/ajax.php?updateShippingAddress', $payload);
    }

    public function updatePno(string $hash, string $pno)
    {
        $payload = [
            'hash' => $hash,
            'pno' => $pno
        ];
        return $this->callPOST('/public/ajax.php?updatePno', $payload);
    }

    public function updateBillingEmail(string $hash, string $email)
    {
        $payload = [
            'hash' => $hash,
            'email' => $email
        ];
        return $this->callPOST('/public/ajax.php?updateBillingEmail', $payload);
    }

    public function step1(string $hash, array $data)
    {
        $data['hash'] = $hash;
        $data['status'] = $data['status'] ?? 'Step1Loaded';
        return $this->callPOST('/public/ajax.php?step1', $data);
    }

    public function getCityFromZipcode(string $hash, string $zipcode)
    {
        $payload = [
            'hash' => $hash,
            'zipcode' => $zipcode
        ];
        echo "Payload: " . json_encode($payload, JSON_PRETTY_PRINT) . "\n";
        return $this->callPOST('/public/ajax.php?getCityFromZipcode', $payload);
    }

    public function validate(string $hash, array $data)
    {
        $data['hash'] = $hash;
        return $this->callPOST('/public/ajax.php?validate', $data);
    }

    public function confirm(string $hash, array $data)
    {
        $data['hash'] = $hash;
        return $this->callPOST('/public/ajax.php?confirm', $data);
    }

    public function cancel(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?cancel', $payload);
    }

    public function getpaymentplans(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?getpaymentplans', $payload);
    }

    public function getPaymentMethods(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?getPaymentMethods', $payload);
    }

    public function removeQuickPaymentCard(string $hash, string $uuid)
    {
        if(preg_match('^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$^', $uuid) !== 1) {
            throw new \InvalidArgumentException('Invalid UUID');
        }
        $payload = [
            'hash' => $hash,
            'uuid' => $uuid
        ];
        return $this->callPOST('/public/ajax.php?removeQuickPaymentCard', $payload);
    }

    public function terms(string $hash, int|string $amount, int|string $method, int|string $method_option = 0)
    {
        $payload = [
            'hash' => $hash,
            'amount' => (string)$amount,
            'method' => (string)$method,
            'method_option' => (string)$method_option
        ];
        return $this->callPOST('/public/ajax.php?terms', $payload);
    }

    public function isRedirectOnSuccess(string $hash)
    {
        $payload = [
            'hash' => $hash
        ];
        return $this->callPOST('/public/ajax.php?isRedirectOnSuccess', $payload);
    }

}