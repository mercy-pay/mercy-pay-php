<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WebhookEndpointService extends AbstractService
{
    /**
     * Enregistre un endpoint.
     *
     * POST /api/v1/webhook_endpoints
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints', $params, $options);
    }

    /**
     * Les endpoints.
     *
     * GET /api/v1/webhook_endpoints
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('webhook_endpoints', $params);
    }

    /**
     * Un endpoint.
     *
     * GET /api/v1/webhook_endpoints/{id}
     */
    public function retrieve(string $id): MercyPayObject
    {
        return $this->object('GET', 'webhook_endpoints/'.self::id($id));
    }

    /**
     * Modifie un endpoint.
     *
     * PATCH /api/v1/webhook_endpoints/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'webhook_endpoints/'.self::id($id), $params);
    }

    /**
     * Supprime un endpoint.
     *
     * DELETE /api/v1/webhook_endpoints/{id}
     */
    public function delete(string $id): MercyPayObject
    {
        return $this->object('DELETE', 'webhook_endpoints/'.self::id($id));
    }

    /**
     * Change le secret ; l'ancien reste valable 24 heures.
     *
     * POST /api/v1/webhook_endpoints/{id}/rotate_secret
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function rotateSecret(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints/'.self::id($id).'/rotate_secret', [], $options);
    }

    /**
     * Envoie un événement webhook.test à cet endpoint.
     *
     * POST /api/v1/webhook_endpoints/{id}/test
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function test(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints/'.self::id($id).'/test', [], $options);
    }
}
