<?php
declare(strict_types=1);

namespace Qvickly\Api\Payment;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Qvickly\Api\Enums\HttpMethod;

use Qvickly\Api\Enums\ReturnDataType;
use Qvickly\Api\Payment\Interfaces\DataObjectInterface;
use Qvickly\Api\Payment\RequestDataObjects\Credentials;
use Qvickly\Api\Payment\RequestDataObjects\Data;

use Qvickly\Api\Payment\RequestDataObjects\Payload;

use Qvickly\Api\Payment\ResponseDataObjects\Data as ReturnData;
use Qvickly\Api\Payment\Exception\PaymentAPIException;
use stdClass;

use function PHPUnit\Framework\throwException;

if(!defined('QVICKLY_PAYMENTAPI_BASE_URL')) {
    define('QVICKLY_PAYMENTAPI_BASE_URL', 'https://api.qvickly.io/');
}
if(!defined('QVICKLY_PAYMENTAPI_CLIENT_VERSION')) {
    define('QVICKLY_PAYMENTAPI_CLIENT_VERSION', '1.0.0');
}
if(!defined('QVICKLY_PAYMENTAPI_SERVER_VERSION')) {
    define('QVICKLY_PAYMENTAPI_SERVER_VERSION', '2.5.0');
}
if(!defined('QVICKLY_PAYMENTAPI_CLIENT_NAME')) {
    define('QVICKLY_PAYMENTAPI_CLIENT_NAME', 'Qvickly:PHP-module:Payment-' . QVICKLY_PAYMENTAPI_CLIENT_VERSION);
}

/**
 * Class PaymentAPI
 * @package Qvickly\Api\Payment
 * @method array getAddress()
 * @method array addPayment(array|Data $data): array|string|DataObjectInterface|stdClass
 * @method array activatePayment(array|Data $data)
 * @method array updatePayment()
 * @method array cancelPayment()
 * @method array creditPayment()
 * @method array duplicatePayment()
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
 * @method array initCheckout(array|Data $data)
 * @method array updateCheckout(array|Data $data)
 * @method array updateStatusLogOfInvoiceByHash(array|Data $data)
 * @method array uploadKalpForm(array|Data $data)
 */
class PaymentAPI
{

    private Client $client;
    const QVICKLY_BASE_URL_KEY = 'BASE_URL';

    /**
     * @param string $eid Qvickly identification number
     * @param string $secret Qvickly secret
     * @param bool $testMode Send flag to indicate test mode
     * @param bool $onlyReturnData Only return data from API
     * @param bool $debugMode Debug mode
     */
    public function __construct(
        private readonly string $eid,
        private readonly string $secret,
        private readonly bool $testMode = false,
        private bool $onlyReturnData = true,
        private bool $debugMode = false,
        private readonly ReturnDataType $returnData = ReturnDataType::EXPORT,
        private array $overrides = []
    )
    {
        $this->client = new Client([
            'base_uri' => $this->overrides[static::QVICKLY_BASE_URL_KEY] ?? QVICKLY_PAYMENTAPI_BASE_URL
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
     * @return stdClass|DataObjectInterface|array|string
     * @throws GuzzleException
     * @throws PaymentAPIException
     */
    public function __call(string $name, array|DataObjectInterface|null $arguments): array|string|DataObjectInterface|stdClass
    {
        $arguments = $arguments ?? [];
        $data = $arguments[0] ?? [];
        if(is_array($data)) {
            $data = new Data($data);
        }
        $url = $this->overrides[static::QVICKLY_BASE_URL_KEY] ?? QVICKLY_PAYMENTAPI_BASE_URL;
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $credentials = new Credentials([
            'id' => $this->eid,
            'hash' => $data->hash($this->secret, true),
            'version' => $this->overrides['SERVER_VERSION'] ?? QVICKLY_PAYMENTAPI_SERVER_VERSION,
            'client' => $this->overrides['CLIENT_NAME'] ?? QVICKLY_PAYMENTAPI_CLIENT_NAME,
            'language' => $this->overrides['LANGUAGE'] ?? 'sv',
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
                // What to return
                if($this->onlyReturnData && isset($json->data)) {
                    $dataToReturn = $json->data;
                } else {
                    $dataToReturn = $json;
                }
                // How to return it
                if($this->returnData === ReturnDataType::RAW) {
                    return $dataToReturn;
                } elseif($this->returnData === ReturnDataType::PROCESSED) {
                    return ReturnData::Parse($dataToReturn, $name);
                } elseif($this->returnData === ReturnDataType::OBJECT) {
                    return ReturnData::Parse($dataToReturn, $name)->export(false);
                } elseif($this->returnData === ReturnDataType::EXPORT) {
                    return ReturnData::Parse($dataToReturn, $name)->export(true);
                }
                return ReturnData::Parse($dataToReturn, $name);
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
     * @return stdClass|DataObjectInterface|array|string
     * @throws Exception
     */
    public function __invoke(string $name, ...$arguments): stdClass|DataObjectInterface|array|string
    {
        return $this->__call($name, $arguments);
    }
}