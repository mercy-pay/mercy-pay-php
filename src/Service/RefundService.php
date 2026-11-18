<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class RefundService extends AbstractService
{
    /**
     * Lister les remboursements.
     *
     * GET /api/v1/refunds
     *
     * @param  array{payment?: string, status?: 'pending'|'processing'|'succeeded'|'failed'|'canceled'}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('refunds', $params);
    }

    /**
     * Lire un remboursement.
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
     * Annuler un remboursement retardé.
     *
     * POST /api/v1/refunds/{id}/cancel
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function cancel(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'refunds/'.self::id($id).'/cancel', $params, $options);
    }
}
