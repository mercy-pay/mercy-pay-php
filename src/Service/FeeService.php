<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;

final class FeeService extends AbstractService
{
    /**
     * Les frais d'un montant, avant de le demander.
     *
     * POST /api/v1/fees/quote
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function quote(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'fees/quote', $params, $options);
    }
}
