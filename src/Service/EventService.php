<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class EventService extends AbstractService
{
    /**
     * Les événements du compte.
     *
     * GET /api/v1/events
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('events', $params);
    }

    /**
     * Un événement.
     *
     * GET /api/v1/events/{id}
     */
    public function retrieve(string $id): MercyPayObject
    {
        return $this->object('GET', 'events/'.self::id($id));
    }

    /**
     * Les envois d'un événement à vos endpoints.
     *
     * GET /api/v1/events/{id}/deliveries
     *
     * @param  array<string, mixed>  $params
     */
    public function deliveries(string $id, array $params = []): Page
    {
        return $this->list('events/'.self::id($id).'/deliveries', $params);
    }

    /**
     * Renvoie un événement à vos endpoints.
     *
     * POST /api/v1/events/{id}/resend
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function resend(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'events/'.self::id($id).'/resend', $params, $options);
    }
}
