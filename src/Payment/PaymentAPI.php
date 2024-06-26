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

use Qvickly\Api\Payment\DataObjects\ReturnData;
use Qvickly\Api\Payment\Exception\PaymentAPIException;
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

    /**
     * @param string $eid Qvickly identification numer
     * @param string $secret Qvickly secret
     * @param bool $testMode Send flag to indicate test mode
     * @param bool $onlyReturnData Only return data from API
     * @param bool $debugMode Debug mode
     */
    public function __construct(private readonly string $eid, private readonly string $secret, private readonly bool $testMode = false, private bool $onlyReturnData = true, private bool $debugMode = false)
    {
        $this->client = new Client([
            'base_uri' => self::QVICKLY_API_BASE_URL
        ]);
    }

    /**
     * Generate hash for credentials data and verification of data
     * @param string $secret
     * @param string $data
     * @return string
     */
    private static function generateHash(string $secret, string $data): string
    {
        return hash_hmac('sha512', $data, $secret);
    }

    /**
     * @param string $name
     * @param Data|array|null $arguments
     * @return stdClass|Payload|Data|array|string
     * @throws GuzzleException
     * @throws PaymentAPIException
     */
    public function __call(string $name, Data|array|null $arguments): stdClass|Payload|Data|array|string
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
            'hash' => $data->hash($this->secret, true),
            'version' => '2.5.0',
            'client' => 'qvickly-php-sdk',
            'language' => 'sv',
            'time' => time(),
            'test' => $this->testMode ? 'true' : 'false',
        ]);
        $payload = new Payload([
            'credentials' => $credentials,
            'data' => $data,
            'function' => $name,
        ]);
        $sendBody = json_encode($payload->export(true));
        if($this->debugMode) {
            var_dump($sendBody);
        }
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
                    throwException(new PaymentAPIException('Invalid hash'));
                }
                if($this->onlyReturnData && isset($json->data)) {
                    return $json->data;
                }
                return $json;
            } catch (Exception $e) {
                if($this->debugMode) {
                    throw $e;
                } elseif($e instanceof PaymentAPIException) {
                    throw $e;
                }
            }
        } catch (Exception $e) {
            if($this->debugMode) {
                throw $e;
            } elseif($e instanceof PaymentAPIException) {
                throw $e;
            }
        }
        return new stdClass();
    }

    /**
     * @param string $name Name of the function to call
     * @param mixed ...$arguments Arguments to pass to the function
     * @return string|array|stdClass
     * @throws Exception
     */
    public function __invoke(string $name, ...$arguments): stdClass|Payload|Data|ReturnData|array|string
    {
        return $this->__call($name, $arguments);
    }
}