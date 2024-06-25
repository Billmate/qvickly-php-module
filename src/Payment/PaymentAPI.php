<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversMethod;
use Qvickly\Api\Enums\HttpMethod;

use Qvickly\Api\Payment\DataObjects\Credentials;
use Qvickly\Api\Payment\DataObjects\Data;

use Qvickly\Api\Payment\DataObjects\Payload;

use stdClass;

use function PHPUnit\Framework\throwException;

/**
 * Class PaymentAPI
 * @package Qvickly\Api\Payment
 * @method array getAddress()
 * @method array addPayment()
 * @method array activatePayment()
 * @method array updatePayment()
 * @method array cancelPayment()
 * @method array creditPayment()
 * @method array getAccountInfo()
 * @method string getTerms()
 * @method array getPaymentPlans()
 * @method array getPaymentInfo()
 * @method array getDuePayments()
 * @method array getSettlements()
 * @method array getSettlementsWithDetails()
 * @method array getCustomersByName()
 * @method array getInvoicesByPno()
 * @method array getInvoicesByCustomer()
 * @method array getExchangeRate()
 * @method array getVatrate()
 * @method array getFees()
 * @method array crediflowSearchParty()
 * @method array getOrderInfo()
 * @method array getOrderByHash()
 * @method array getInvoiceByHash()
 * @method array createInvoiceFromOrderHash()
 * @method array getAPICredentials()
 */
class PaymentAPI
{
    const QVICKLY_API_BASE_URL = 'https://api.qvickly.io/';
    private Client $client;

    public function __construct(private readonly string $eid, private readonly string $secret, private readonly bool $testMode = false, private bool $onlyReturnData = true)
    {
        $this->client = new Client([
            'base_uri' => self::QVICKLY_API_BASE_URL
        ]);
    }

    private static function generateHash(string $secret, string $data): string
    {
        return hash_hmac('sha512', $data, $secret);
    }
    public function __call(string $name, Data|array|null $arguments): string|array|stdClass
    {
        $arguments = $arguments ?? [];
        $data = $arguments[0] ?? [];
        if(is_array($data)) {
            $data = new Data($data);
        }
        $url = static::QVICKLY_API_BASE_URL;
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $credentials = new Credentials([
            'id' => $this->eid,
            'hash' => $data->hash($this->secret),
            'version' => '2.5.0',
            'client' => 'qvickly-php-sdk',
            'language' => 'sv',
            'time' => time(),
            'testMode' => $this->testMode ? 'true' : 'false',
        ]);
        $payload = new Payload([
            'credentials' => $credentials,
            'data' => $data,
            'function' => $name,
        ]);
        $sendBody = json_encode($payload->export());
        $request = new Request(HttpMethod::POST->value, $url, $headers, $sendBody);
        try {
            $response = $this->client->send($request);
            $body = $response->getBody()->getContents();
            try {
                $json = json_decode($body);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    return $body;
                }
                if(isset($json->credentials?->hash) && $json->credentials->hash !== static::generateHash($this->secret, json_encode($json->data))) {
                    throwException(new Exception('Invalid hash'));
                }
                if($this->onlyReturnData && isset($json->data)) {
                    return $json->data;
                }
                return $json;
            } catch (Exception $e) {
                print("JSON Exception ");
                print_r($e->getMessage());
            }
        } catch (GuzzleException $e) {
            print("GuzzleException ");
            print_r($e->getMessage());
        }
        return new stdClass();
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}