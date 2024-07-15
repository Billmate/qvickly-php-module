<?php

namespace Qvickly\Api\Traits;

trait RequestTraits
{
    private function buildUrl(string $path): string
    {
        $base = rtrim($this->overrides[static::QVICKLY_BASE_URL_KEY] ?? static::QVICKLY_BASE_URL, '/');
        $path = ltrim($path, '/');
        return sprintf('%s/%s', $base, $path);
    }
    private function buildHeaders(array $headers = []): array
    {
        return array_merge($headers,
            [
                'Content-Type' => 'application/json',
            ]
        );
    }
    private function makePostData(array $data, string $subPrefix = ''): string|array
    {
        $string = [];
        foreach($data as $key => $value) {
            $currentKey = $subPrefix ? $subPrefix . '[' . rawurlencode($key) . ']' : rawurlencode($key);
            if(is_array($value)) {
                $string[] = $this->makePostData($value, $currentKey);
            } else {
                $string[] = $currentKey . "=" . rawurlencode($value);
            }
        }
        return implode('&', $string);
    }
}