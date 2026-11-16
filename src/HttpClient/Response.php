<?php

declare(strict_types=1);

namespace MercyPay\HttpClient;

/**
 * A raw HTTP answer: its status, its headers (names in lower case) and its body.
 */
final class Response
{
    /**
     * @param  array<string, string>  $headers
     */
    public function __construct(
        public readonly int $status,
        public readonly array $headers,
        public readonly string $body,
    ) {}

    public function header(string $name): ?string
    {
        return $this->headers[strtolower($name)] ?? null;
    }
}
