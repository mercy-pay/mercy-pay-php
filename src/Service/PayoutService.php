<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PayoutService extends AbstractService
{
    /**
     * Demander un retrait.
     *
     * POST /api/v1/payouts
     *
     * @param  array{amount: int, currency: string, destination_id?: string, destination?: array{type: 'mobile_money', country: string, provider: string, phone: string}, metadata?: array<string, mixed>}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payouts', $params, $options);
    }

    /**
     * Lister les retraits.
     *
     * GET /api/v1/payouts
     *
     * @param  array{status?: 'requested'|'approved'|'processing'|'paid'|'failed'|'rejected'|'canceled', currency?: string, from?: string, to?: string, limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payouts', $params);
    }

    /**
     * Lire un retrait.
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
