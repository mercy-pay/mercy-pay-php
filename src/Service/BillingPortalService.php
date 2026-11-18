<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class BillingPortalService extends AbstractService
{
    /**
     * Ouvrir l'espace client.
     *
     * POST /api/v1/billing_portal/sessions
     *
     * @param  array{customer_email: string, return_url?: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function createSession(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'billing_portal/sessions', $params, $options);
    }
}
