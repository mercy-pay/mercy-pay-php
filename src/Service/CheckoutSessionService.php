<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\Exception\WaitTimeoutException;
use MercyPay\MercyPayObject;
use MercyPay\Page;

final class CheckoutSessionService extends AbstractService
{
    /**
     * Créer une session.
     *
     * POST /api/v1/checkout_sessions
     *
     * @param  array{amount: int, currency: string, description?: string, payer_name?: string, payer_email?: string, payer_phone?: string, success_url?: string, cancel_url?: string, payment_method_types?: list<'mobile_money'|'card'>, allowed_countries?: list<string>, ui_mode?: 'hosted'|'embedded', metadata?: array<string, mixed>}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'checkout_sessions', $params, $options);
    }

    /**
     * Lister les sessions.
     *
     * GET /api/v1/checkout_sessions
     *
     * @param  array{status?: 'created'|'pending_payment'|'completed'|'expired'|'cancelled', from?: string, to?: string, limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('checkout_sessions', $params);
    }

    /**
     * Lire une session.
     *
     * GET /api/v1/checkout_sessions/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'checkout_sessions/'.self::id($id), $params);
    }

    /**
     * Expirer une session.
     *
     * POST /api/v1/checkout_sessions/{id}/expire
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function expire(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'checkout_sessions/'.self::id($id).'/expire', $params, $options);
    }

    /**
     * Reads the session until it is completed, expired or cancelled.
     *
     * @param  array{timeout?: int, interval?: int}  $options  milliseconds: 120 000 and 2 000 by default
     *
     * @throws WaitTimeoutException with the last state, when the timeout passes first
     */
    public function waitForCompletion(string $id, array $options = []): MercyPayObject
    {
        return $this->poll(
            fn (): MercyPayObject => $this->retrieve($id),
            ['completed', 'expired', 'cancelled'],
            $options,
            "La session {$id}",
        );
    }
}
