<?php
declare(strict_types=1);

namespace Qvickly\Api\Portal;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\ResponseInterface;
use Qvickly\Api\Enums\HttpMethod;

if(!defined('QVICKLY_PORTALAPI_BASE_URL')) {
    define('QVICKLY_PORTALAPI_BASE_URL', 'https://api.online.billmate.se/');
}
if(!defined('QVICKLY_PORTALAPI_CLIENT_VERSION')) {
    define('QVICKLY_PORTALAPI_CLIENT_VERSION', '1.0.0');
}
if(!defined('QVICKLY_PORTALAPI_SERVER_VERSION')) {
    define('QVICKLY_PORTALAPI_SERVER_VERSION', '2.5.0');
}
if(!defined('QVICKLY_PORTALAPI_CLIENT_NAME')) {
    define('QVICKLY_PORTALAPI_CLIENT_NAME', 'Qvickly:PHP-module:Portal-' . QVICKLY_PORTALAPI_CLIENT_VERSION);
}

class PortalAPI
{
    private Client $client;

    public function __construct(
        private string|null $token,
        private bool $testMode = false,
        private array $overrides = []
    )
    {
        $this->client = new Client([
            'base_uri' => $this->overrides['BASE_URL'] ?? QVICKLY_PORTALAPI_BASE_URL
        ]);
    }

    private function send(string $method, string $url, string $data = '', array $headers = [], array $params = [])
    {
        $headers['Authorization'] = $headers['Authorization'] ?? 'Bearer ' . $this->token;
        $headers['Content-Type'] = $headers['Content-Type'] ?? 'application/json';
        $fullUrl = ($this->overrides['BASE_URL'] ?? QVICKLY_PORTALAPI_BASE_URL) . $url;
        $query = http_build_query($params);
        if ($query) {
            if(strpos($fullUrl, '?') === false) {
                $fullUrl .= '?';
            } else {
                $fullUrl .= '&';
            }
            $fullUrl .= $query;
        }
        $request = new Request($method, $fullUrl, $headers, $data);
        try {
            $response = $this->client->send($request);
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            return [
                'error' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }
    }

    public function get($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::GET->value, $url, $sendData, $headers, $params);
        return $response;
    }

    public function post($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::POST->value, $url, $sendData, $headers, $params);
        return $response;
    }

    public function put($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::PUT->value, $url, $sendData, $headers, $params);
        return $response;
    }

    public function patch($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::PATCH->value, $url, $sendData, $headers, $params);
        return $response;
    }

    public function head($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::HEAD->value, $url, $sendData, $headers, $params);
        return $response;
    }

    public function delete($url, $data = null, $headers = [], array $params = [])
    {
        $sendData = $data ? json_encode($data) : '';
        $response = $this->send(HttpMethod::DELETE->value, $url, $sendData, $headers, $params);
        return $response;
    }

}