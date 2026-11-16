<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class RefundService extends AbstractService
{
    /**
     * Les remboursements.
     *
     * GET /api/v1/refunds
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('refunds', $params);
    }

    /**
     * Un remboursement.
     *
     * GET /api/v1/refunds/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'refunds/'.self::id($id), $params);
    }

    /**
     * Annule un remboursement pas encore parti.
     *
     * POST /api/v1/refunds/{id}/cancel
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function cancel(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'refunds/'.self::id($id).'/cancel', [], $options);
    }
}
