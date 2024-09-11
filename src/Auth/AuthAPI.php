<?php
declare(strict_types=1);

namespace Qvickly\Api\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Qvickly\Api\Enums\HttpMethod;
use Qvickly\Api\Traits\RequestTraits;

if(!defined('QVICKLY_AUTHAPI_BASE_URL')) {
    define('QVICKLY_AUTHAPI_BASE_URL', 'https://auth.billmate.se/');
}

class AuthAPI
{
    use RequestTraits;
    private Client $client;
    const QVICKLY_BASE_URL = QVICKLY_AUTHAPI_BASE_URL;
    const QVICKLY_BASE_URL_KEY = 'BASE_URL';

    public function __construct(private bool $debugMode = false, private array $overrides = [])
    {
        $this->client = new Client([
            'base_uri' => $this->overrides[static::QVICKLY_BASE_URL_KEY] ?? QVICKLY_AUTHAPI_BASE_URL
        ]);
    }

    private function get($url, $headers)
    {
        $request = new Request(HttpMethod::GET->value, $url, $headers);
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
    private function post(string $url, array $headers, string|null $data = null)
    {
        $request = new Request(HttpMethod::POST->value, $url, $headers, $data);
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
    private function delete(string $url, array $headers, string|null $data = null)
    {
        $request = new Request(HttpMethod::DELETE->value, $url, $headers, $data);
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

    public function verify(string $token)
    {
        $url = $this->buildUrl('me');
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        return $this->get($url, $headers);
    }

    public function bankidLogin(string|null $personalNumber = null)
    {
        $data = null;
        if($personalNumber !== null) {
            $data = json_encode([
                'personalNumber' => $personalNumber ?? '',
            ]);
        }
        $url = $this->buildUrl('bankidV6');
        $headers = $this->buildHeaders();
        $result = $this->post($url, $headers, $data);
        if(is_array($result) && array_key_exists('orderRef', $result)) {
            if(array_key_exists('autoStartToken', $result)) {
                $result['autoStartUrl'] = sprintf("bankid:///?autostarttoken=%s&redirect=null", $result['autoStartToken']);
            }
            if(array_key_exists('qrStartToken', $result) && array_key_exists('hashes', $result)) {
                $result['authList'] = [];
                foreach ($result['hashes'] as $index => $hash) {
                    $result['authList'][] = sprintf("bankid.%s.%s.%s", $result['qrStartToken'], $index, $hash);
                }
            }
        }
        return $result;
    }

    public function bankidCancel(string $orderRef): string|array
    {
        $url = $this->buildUrl('bankidV6/' . $orderRef);
        $headers = $this->buildHeaders();
        return $this->delete($url, $headers);
    }

    public function bankidCollect(string $orderRef): string|array
    {
        $url = $this->buildUrl('bankidV6/' . $orderRef);
        $headers = $this->buildHeaders();
        return $this->get($url, $headers);
    }

    public function bankidSign(string $token, string $name, string $language, string $nonVisibleData, string|null $personalNumber = null)
    {
        $data = [
            'language' => $language,
            'userNonVisibleData' => $nonVisibleData
        ];
        if($personalNumber !== null) {
            $data['personalNumber'] = $personalNumber;
        }
        $url = $this->buildUrl('bankidV6/sign/' . $name);
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        var_dump($data);
        $result = $this->post($url, $headers, json_encode($data));
        if(is_array($result) && array_key_exists('orderRef', $result)) {
            if(array_key_exists('autoStartToken', $result)) {
                $result['autoStartUrl'] = sprintf("bankid:///?autostarttoken=%s&redirect=null", $result['autoStartToken']);
            }
            if(array_key_exists('qrStartToken', $result) && array_key_exists('hashes', $result)) {
                $result['signList'] = [];
                foreach ($result['hashes'] as $index => $hash) {
                    $result['signList'][] = sprintf("bankid.%s.%s.%s", $result['qrStartToken'], $index, $hash);
                }
            }
        }
        return $result;
    }

    public function bankidSignCollect(string $token, string $orderRef): string|array
    {
        $url = $this->buildUrl('bankidV6/sign/collect/' . $orderRef);
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        return $this->get($url, $headers);
    }

    public function login(string $username, string $password): string|array
    {
        $url = $this->buildUrl('login');
        $headers = $this->buildHeaders();
        $payload = [
            'username' => $username,
            'password' => $password,
        ];
        $sendBody = json_encode($payload);
        if($this->debugMode) {
            var_dump($sendBody);
        }
        $result = $this->post($url, $headers, $sendBody);
        if(is_array($result) && array_key_exists('token', $result)) {
            return $result['token'];
        }
        return $result;
    }

    public function me(string $token): string|array
    {
        $url = $this->buildUrl('me');
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        return $this->get($url, $headers);
    }

    public function address(string $token): string|array
    {
        // Make sure the token is valid and contain the personal number
        $tokenParts = explode('.', $token);
        if(count($tokenParts) !== 3) {
            return [
                'error' => 'Invalid token structure',
                'code' => 400
            ];
        }
        $tokenData = json_decode(base64_decode($tokenParts[1]), true);
        if(!is_array($tokenData)) {
            return [
                'error' => 'Invalid token content',
                'code' => 400
            ];
        }
        if(!isset($tokenData['User']) || !isset($tokenData['User']['personalNumber'])) {
            return [
                'error' => 'No personalNumber in token',
                'code' => 400
            ];
        }
        // Token is OK. Get the address
        $url = $this->buildUrl('me/address');
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        return $this->get($url, $headers);
    }

    public function merchant(string $token, string $merchantId): string|array
    {
        $url = $this->buildUrl('merchant/' . $merchantId);
        $headers = $this->buildHeaders([
            'x-auth-token' => $token,
        ]);
        return $this->get($url, $headers);
    }

}