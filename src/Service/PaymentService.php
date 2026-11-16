<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PaymentService extends AbstractService
{
    /**
     * Une charge directe, sans page de paiement.
     *
     * POST /api/v1/payments
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payments', $params, $options);
    }

    /**
     * Les paiements, les plus récents d'abord.
     *
     * GET /api/v1/payments
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payments', $params);
    }

    /**
     * Un paiement.
     *
     * GET /api/v1/payments/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'payments/'.self::id($id), $params);
    }

    /**
     * Rembourse tout ou partie d'un paiement.
     *
     * POST /api/v1/payments/{id}/refunds
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function refund(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payments/'.self::id($id).'/refunds', $params, $options);
    }
}
