<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WalletTransferService extends AbstractService
{
    /**
     * Lister les virements entre soldes.
     *
     * GET /api/v1/wallet_transfers
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('wallet_transfers', $params);
    }

    /**
     * Virer entre deux soldes.
     *
     * POST /api/v1/wallet_transfers
     *
     * @param  array{from_wallet: string, to_wallet: string, amount: int}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'wallet_transfers', $params, $options);
    }
}
