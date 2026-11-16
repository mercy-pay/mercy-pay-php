<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WalletService extends AbstractService
{
    /**
     * Les soldes, un par canal.
     *
     * GET /api/v1/wallets
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('wallets', $params);
    }
}
