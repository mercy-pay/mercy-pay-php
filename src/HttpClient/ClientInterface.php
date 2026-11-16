<?php

declare(strict_types=1);

namespace MercyPay\HttpClient;

use MercyPay\Exception\ApiConnectionException;

/**
 * Sends one HTTP request. Replace it to route calls through your own HTTP stack.
 */
interface ClientInterface
{
    /**
     * @param  array<string, string>  $headers
     *
     * @throws ApiConnectionException when no answer came back
     */
    public function send(string $method, string $url, array $headers, ?string $body, int $timeout): Response;
}
