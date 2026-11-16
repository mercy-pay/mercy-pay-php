<?php

declare(strict_types=1);

namespace MercyPay\Service;

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

    protected static function id(string $id): string
    {
        return rawurlencode($id);
    }
}
