<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class AccountService extends AbstractService
{
    /**
     * Lire le compte.
     *
     * GET /api/v1/account
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(array $params = []): MercyPayObject
    {
        return $this->object('GET', 'account', $params);
    }
}
