<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\Exception\WaitTimeoutException;
use MercyPay\MercyPayClient;
use MercyPay\MercyPayObject;
use MercyPay\Page;

/**
 * What every service shares: send a call, unwrap {"data": …}, turn a list into a Page.
 */
abstract class AbstractService
{
    final public function __construct(protected readonly MercyPayClient $client) {}

    /**
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    protected function object(string $method, string $path, array $params = [], array $options = []): MercyPayObject
    {
        $body = $this->client->request($method, $path, $params, $options);
        $data = $body['data'] ?? $body;

        return MercyPayObject::from(is_array($data) ? $data : []);
    }

    /**
     * @param  array<string, mixed>  $params
     */
    protected function list(string $path, array $params = []): Page
    {
        $body = $this->client->request('GET', $path, $params);
        $rows = is_array($body['data'] ?? null) ? $body['data'] : [];

        return new Page(
            array_values(array_map(fn (mixed $row): MercyPayObject => MercyPayObject::from(is_array($row) ? $row : []), $rows)),
            (bool) ($body['has_more'] ?? false),
            isset($body['next_cursor']) ? (string) $body['next_cursor'] : null,
            fn (array $next): Page => $this->list($path, $next),
            $params,
        );
    }

    /**
     * Reads again, a little more spaced each time, until the status is final or the timeout
     * passes.
     *
     * @param  callable(): MercyPayObject  $read
     * @param  list<string>  $final
     * @param  array{timeout?: int, interval?: int}  $options
     */
    protected function poll(callable $read, array $final, array $options, string $what): MercyPayObject
    {
        $deadline = hrtime(true) / 1e6 + ($options['timeout'] ?? 120_000);
        $delay = $options['interval'] ?? 2_000;
        $current = $read();

        while (! in_array($current['status'], $final, true)) {
            if (hrtime(true) / 1e6 + $delay > $deadline) {
                throw new WaitTimeoutException("{$what} n'a pas atteint de statut final à temps (statut : ".$current['status'].').', $current);
            }

            $this->client->pause($delay);
            $delay = min((int) round($delay * 1.5), 10_000);
            $current = $read();
        }

        return $current;
    }

    protected static function id(string $id): string
    {
        return rawurlencode($id);
    }
}
