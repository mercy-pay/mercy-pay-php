<?php

declare(strict_types=1);

namespace MercyPay\HttpClient;

use MercyPay\Exception\ApiConnectionException;

/**
 * The default transport: curl, available on every PHP host.
 */
final class CurlClient implements ClientInterface
{
    public function send(string $method, string $url, array $headers, ?string $body, int $timeout): Response
    {
        $handle = curl_init($url);

        if ($handle === false) {
            throw new ApiConnectionException('Impossible de préparer la requête vers Mercy Pay.');
        }

        $responseHeaders = [];
        $lines = [];

        foreach ($headers as $name => $value) {
            $lines[] = $name.': '.$value;
        }

        curl_setopt_array($handle, [
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $lines,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CONNECTTIMEOUT => min(10, $timeout),
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_HEADERFUNCTION => function ($curl, string $line) use (&$responseHeaders): int {
                $parts = explode(':', $line, 2);

                if (count($parts) === 2) {
                    $responseHeaders[strtolower(trim($parts[0]))] = trim($parts[1]);
                }

                return strlen($line);
            },
        ]);

        if ($body !== null) {
            curl_setopt($handle, CURLOPT_POSTFIELDS, $body);
        }

        $result = curl_exec($handle);

        if ($result === false) {
            $error = curl_error($handle);

            throw new ApiConnectionException("Mercy Pay n'a pas pu être joint : {$error}");
        }

        $status = (int) curl_getinfo($handle, CURLINFO_RESPONSE_CODE);

        return new Response($status, $responseHeaders, (string) $result);
    }
}
