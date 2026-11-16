<?php

declare(strict_types=1);

namespace MercyPay\Tests\Support;

use MercyPay\Exception\ApiConnectionException;
use MercyPay\HttpClient\ClientInterface;
use MercyPay\HttpClient\Response;

/** Answers from a queue and remembers every request: no network in the tests. */
final class FakeHttpClient implements ClientInterface
{
    /** @var list<array{method: string, url: string, headers: array<string, string>, body: ?string}> */
    public array $requests = [];

    /** @var list<Response|ApiConnectionException> */
    private array $queue = [];

    public function queue(Response|ApiConnectionException ...$answers): self
    {
        array_push($this->queue, ...$answers);

        return $this;
    }

    public static function json(int $status, array $body, array $headers = []): Response
    {
        return new Response($status, array_change_key_case($headers), json_encode($body, JSON_THROW_ON_ERROR));
    }

    public function send(string $method, string $url, array $headers, ?string $body, int $timeout): Response
    {
        $this->requests[] = compact('method', 'url', 'headers', 'body');
        $answer = array_shift($this->queue) ?? self::json(200, ['data' => []]);

        if ($answer instanceof ApiConnectionException) {
            throw $answer;
        }

        return $answer;
    }
}
