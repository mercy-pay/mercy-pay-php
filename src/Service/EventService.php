<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class EventService extends AbstractService
{
    /**
     * Lister les événements.
     *
     * GET /api/v1/events
     *
     * @param  array{type?: string, limit?: int, starting_after?: string, ending_before?: string}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('events', $params);
    }

    /**
     * Lire un événement.
     *
     * GET /api/v1/events/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'events/'.self::id($id), $params);
    }

    /**
     * Lister les envois d'un événement.
     *
     * GET /api/v1/events/{id}/deliveries
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string}  $params
     */
    public function deliveries(string $id, array $params = []): Page
    {
        return $this->list('events/'.self::id($id).'/deliveries', $params);
    }

    /**
     * Renvoyer un événement.
     *
     * POST /api/v1/events/{id}/resend
     *
     * @param  array{webhook_endpoint: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function resend(string $id, array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'events/'.self::id($id).'/resend', $params, $options);
    }
}
