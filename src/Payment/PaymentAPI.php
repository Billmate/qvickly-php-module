<?php

namespace Qvickly\Api\Payment;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Psr7\Request;
use Qvickly\Api\Enums\HttpMethod;

use function PHPUnit\Framework\throwException;

class PaymentAPI
{
    const QVICKLY_API_BASE_URL = 'https://api.qvickly.io/';
    private Client $client;

    public function __construct(private readonly string $eid, private readonly string $secret, private readonly bool $testMode = false)
    {
        $this->client = new Client([
            'base_uri' => self::QVICKLY_API_BASE_URL
        ]);
    }

    private static function generateHash(string $secret, string $data): string
    {
        return hash_hmac('sha512', $data, $secret);
    }
    public function __call(string $name, array|null $arguments): string|array|\stdClass
    {
        $arguments = $arguments ?? [];
        $url = static::QVICKLY_API_BASE_URL;
        $headers = [
            'Content-Type' => 'application/json',
        ];
        $data = json_encode($arguments[0]) ?? [];
        $rawBody = [
            'credentials' => [
                'id' => $this->eid,
                'hash' => static::generateHash($this->secret, $data),
                'version' => '2.5.0',
                'client' => 'qvickly-php-sdk',
                'language' => 'sv',
                'time' => time(),
                'testMode' => $this->testMode ? 'true' : 'false',
            ],
            'data' => $arguments[0] ?? [],
            'function' => $name,
        ];
        $sendBody = json_encode($rawBody);
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
                    throwException(new \Exception('Invalid hash'));
                }
                return $json;
            } catch (\Exception $e) {
                print("JSON Exception ");
                print_r($e->getMessage());
            }
        } catch (GuzzleException $e) {
            print("GuzzleException ");
            print_r($e->getMessage());
        }
        return new \stdClass();
    }

    public function __invoke()
    {
        // TODO: Implement __invoke() method.
    }
}