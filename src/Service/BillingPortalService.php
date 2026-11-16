<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;

final class BillingPortalService extends AbstractService
{
    /**
     * Ouvre l'espace client d'un client.
     *
     * POST /api/v1/billing_portal/sessions
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function createSession(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'billing_portal/sessions', $params, $options);
    }
}
