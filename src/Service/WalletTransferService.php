<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class WalletTransferService extends AbstractService
{
    /**
     * Les transferts entre soldes.
     *
     * GET /api/v1/wallet_transfers
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('wallet_transfers', $params);
    }

    /**
     * Transfère entre deux de vos soldes.
     *
     * POST /api/v1/wallet_transfers
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'wallet_transfers', $params, $options);
    }
}
