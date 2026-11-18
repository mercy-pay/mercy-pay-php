<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\Exception\WaitTimeoutException;
use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PaymentService extends AbstractService
{
    /**
     * Encaisser un paiement.
     *
     * POST /api/v1/payments
     *
     * @param  array{amount: int, currency: string, payment_method?: 'mobile_money'|'card', country?: string, provider?: string, phone?: string, payer_name?: string, payer_email?: string, description?: string, metadata?: array<string, mixed>}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payments', $params, $options);
    }

    /**
     * Lister les paiements.
     *
     * GET /api/v1/payments
     *
     * @param  array{status?: 'pending'|'processing'|'succeeded'|'failed'|'canceled'|'expired'|'refunded'|'disputed', from?: string, to?: string, limit?: int, starting_after?: string, ending_before?: string, expand?: list<string>, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payments', $params);
    }

    /**
     * Lire un paiement.
     *
     * GET /api/v1/payments/{id}
     *
     * @param  array{expand?: list<string>}  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'payments/'.self::id($id), $params);
    }

    /**
     * Rembourser un paiement.
     *
     * POST /api/v1/payments/{id}/refunds
     *
     * @param  array{amount?: int, reason?: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function refund(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payments/'.self::id($id).'/refunds', $params, $options);
    }

    /**
     * Reads the payment until it is final (succeeded, failed, canceled, expired…), for a
     * script or a test. On a server, prefer the payment.succeeded webhook.
     *
     * @param  array{timeout?: int, interval?: int}  $options  milliseconds: 120 000 and 2 000 by default
     *
     * @throws WaitTimeoutException with the last state, when the timeout passes first
     */
    public function waitForFinalStatus(string $id, array $options = []): MercyPayObject
    {
        return $this->poll(
            fn (): MercyPayObject => $this->retrieve($id),
            ['succeeded', 'failed', 'canceled', 'expired', 'refunded', 'disputed'],
            $options,
            "Le paiement {$id}",
        );
    }
}
