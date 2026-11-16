<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;

final class ProviderStatusService extends AbstractService
{
    /**
     * L'état des opérateurs en ce moment.
     *
     * GET /api/v1/provider_status
     */
    public function retrieve(): MercyPayObject
    {
        return $this->object('GET', 'provider_status');
    }
}
