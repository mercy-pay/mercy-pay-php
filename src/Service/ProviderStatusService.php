<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class ProviderStatusService extends AbstractService
{
    /**
     * État des opérateurs.
     *
     * GET /api/v1/provider_status
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(array $params = []): MercyPayObject
    {
        return $this->object('GET', 'provider_status', $params);
    }
}
