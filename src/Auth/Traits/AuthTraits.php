<?php

namespace Qvickly\Api\Auth\Traits;

trait AuthTraits
{
    private function buildUrl(string $path): string
    {
        $base = rtrim($this->overrides['BASE_URL'] ?? QVICKLY_AUTHAPI_BASE_URL, '/');
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
}