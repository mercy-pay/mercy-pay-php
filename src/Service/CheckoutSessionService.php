<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class CheckoutSessionService extends AbstractService
{
    /**
     * Crée une session : envoyez le client sur son checkout_url.
     *
     * POST /api/v1/checkout_sessions
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'checkout_sessions', $params, $options);
    }

    /**
     * Les sessions, les plus récentes d'abord.
     *
     * GET /api/v1/checkout_sessions
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('checkout_sessions', $params);
    }

    /**
     * Une session.
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
     * Ferme une session avant son expiration.
     *
     * POST /api/v1/checkout_sessions/{id}/expire
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function expire(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'checkout_sessions/'.self::id($id).'/expire', [], $options);
    }
}
