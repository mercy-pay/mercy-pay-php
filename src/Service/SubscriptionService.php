<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class SubscriptionService extends AbstractService
{
    /**
     * Crée un abonnement.
     *
     * POST /api/v1/subscriptions
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions', $params, $options);
    }

    /**
     * Les abonnements.
     *
     * GET /api/v1/subscriptions
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('subscriptions', $params);
    }

    /**
     * Un abonnement.
     *
     * GET /api/v1/subscriptions/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'subscriptions/'.self::id($id), $params);
    }

    /**
     * Modifie un abonnement.
     *
     * PATCH /api/v1/subscriptions/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'subscriptions/'.self::id($id), $params);
    }

    /**
     * Annule un abonnement, tout de suite ou en fin de période.
     *
     * POST /api/v1/subscriptions/{id}/cancel
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function cancel(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/cancel', $params, $options);
    }

    /**
     * Réactive un abonnement annulé en fin de période.
     *
     * POST /api/v1/subscriptions/{id}/reactivate
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function reactivate(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/reactivate', [], $options);
    }

    /**
     * Sandbox : passe à l'échéance suivante tout de suite.
     *
     * POST /api/v1/subscriptions/{id}/advance
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function advance(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/advance', [], $options);
    }

    /**
     * Met un abonnement en pause.
     *
     * POST /api/v1/subscriptions/{id}/pause
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function pause(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/pause', $params, $options);
    }

    /**
     * Reprend un abonnement en pause.
     *
     * POST /api/v1/subscriptions/{id}/resume
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function resume(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/resume', [], $options);
    }

    /**
     * Les factures d'un abonnement.
     *
     * GET /api/v1/subscriptions/{id}/invoices
     *
     * @param  array<string, mixed>  $params
     */
    public function invoices(string $id, array $params = []): Page
    {
        return $this->list('subscriptions/'.self::id($id).'/invoices', $params);
    }
}
