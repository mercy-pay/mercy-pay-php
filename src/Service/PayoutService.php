<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PayoutService extends AbstractService
{
    /**
     * Demande un retrait.
     *
     * POST /api/v1/payouts
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payouts', $params, $options);
    }

    /**
     * Les retraits.
     *
     * GET /api/v1/payouts
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payouts', $params);
    }

    /**
     * Un retrait.
     *
     * GET /api/v1/payouts/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'payouts/'.self::id($id), $params);
    }
}
