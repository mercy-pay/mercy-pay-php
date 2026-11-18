<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WalletService extends AbstractService
{
    /**
     * Lire les soldes.
     *
     * GET /api/v1/wallets
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('wallets', $params);
    }
}
