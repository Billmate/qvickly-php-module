<?php
declare(strict_types=1);

namespace Qvickly\Api\Checkout;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Qvickly\Api\Enums\HttpMethod;
use Qvickly\Api\Payment\PaymentAPI;
use Qvickly\Api\Payment\RequestDataObjects\Data;
use Qvickly\Api\Payment\ResponseDataObjects\Data as ResponseData;
use Qvickly\Api\Traits\RequestTraits;

if(!defined('QVICKLY_CHECKOUTAPI_BASE_URL')) {
    define('QVICKLY_CHECKOUTAPI_BASE_URL', 'https://api.qvickly.io/');
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
    const QVICKLY_BASE_URL = QVICKLY_CHECKOUTAPI_BASE_URL;

    public function __construct(private string $eid, private string $secret, private bool $testMode = false, private array $overrides = [])
    {
        $this->paymentAPI = new PaymentAPI(eid: $eid, secret: $secret, testMode: $testMode, overrides: $overrides);
        $this->client = new Client([
            'base_uri' => $this->overrides['BASE_URL'] ?? QVICKLY_CHECKOUTAPI_BASE_URL
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
        echo "Send data to $fullUrl\n";
        $request = new Request($method, $fullUrl, $headers, $data);
        try {
            $response = $this->client->send($request);
            echo "Response: \n";
            var_dump($response->getBody()->getContents());
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            echo "Da shit hit the fan\n";
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
        return $this->send(HttpMethod::POST->value, $url, $this->makePostData($data));
    }

    public function initCheckout(array|Data $data): array|ResponseData
    {
        $result = $this->paymentAPI->initCheckout($data);
        if(is_array($result) && array_key_exists('url', $result)) {
            $url = parse_url($result['url']);
            $pathParts = explode('/', ltrim($url['path'], '/'));
            if(count($pathParts) > 1) {
                var_dump($pathParts);
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
        echo "Call to $url\n";
        // return $this->callGET($url);
    }

    public function isPaid(array|Data $baseData)
    {

        return $this->callPOST('/public/isPaid', $baseData);
    }

    public function checkoutStatus()
    {

    }

    public function bankidopen()
    {

    }

    public function bankidclose()
    {

    }

    public function cancelInvoice()
    {

    }

    public function get()
    {

    }

    public function clearCheckout()
    {

    }

    public function updateStatus()
    {

    }

    public function updateMessage()
    {

    }

    public function updatePaymentMethod()
    {

    }

    public function updatePaymentPlan()
    {

    }

    public function updateBillingAddress()
    {

    }

    public function updateShippingAddress()
    {

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

    public function step1()
    {

    }

    public function getCityFromZipcode()
    {

    }

    public function validate()
    {

    }

    public function confirm()
    {

    }

    public function cancel()
    {

    }

    public function getpaymentplans()
    {

    }

    public function getPaymentMethods()
    {

    }

    public function removeQuickPaymentCard()
    {

    }

    public function terms()
    {

    }

    public function isRedirectOnSuccess()
    {

    }

}