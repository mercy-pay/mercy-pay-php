<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WebhookEndpointService extends AbstractService
{
    /**
     * Créer un endpoint.
     *
     * POST /api/v1/webhook_endpoints
     *
     * @param  array{url: string, events: list<string>, environment?: 'sandbox'|'live'|null}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints', $params, $options);
    }

    /**
     * Lister les endpoints.
     *
     * GET /api/v1/webhook_endpoints
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('webhook_endpoints', $params);
    }

    /**
     * Lire un endpoint.
     *
     * GET /api/v1/webhook_endpoints/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'webhook_endpoints/'.self::id($id), $params);
    }

    /**
     * Modifier un endpoint.
     *
     * PATCH /api/v1/webhook_endpoints/{id}
     *
     * @param  array{url?: string, events?: list<string>, environment?: 'sandbox'|'live'|null, status?: 'enabled'|'disabled'}  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'webhook_endpoints/'.self::id($id), $params);
    }

    /**
     * Supprimer un endpoint.
     *
     * DELETE /api/v1/webhook_endpoints/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function delete(string $id, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'webhook_endpoints/'.self::id($id), $params);
    }

    /**
     * Changer le secret.
     *
     * POST /api/v1/webhook_endpoints/{id}/rotate_secret
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function rotateSecret(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints/'.self::id($id).'/rotate_secret', $params, $options);
    }

    /**
     * Envoyer un test.
     *
     * POST /api/v1/webhook_endpoints/{id}/test
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function test(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'webhook_endpoints/'.self::id($id).'/test', $params, $options);
    }
}
