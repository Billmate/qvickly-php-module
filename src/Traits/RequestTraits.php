<?php

namespace Qvickly\Api\Traits;

trait RequestTraits
{
    private function buildUrl(string $path): string
    {
        $base = rtrim($this->overrides['BASE_URL'] ?? static::QVICKLY_BASE_URL, '/');
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
    private function makePostData(array $data): string
    {
        $string = [];
        foreach($data as $key => $value) {
            $string[] = $key . "=" . $value;
        }
        return implode('&', $string);
    }
}