<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class FeeService extends AbstractService
{
    /**
     * Calculer les frais d'un paiement.
     *
     * POST /api/v1/fees/quote
     *
     * @param  array{amount: int, currency: string, payment_method: 'mobile_money'|'card', country?: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function quote(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'fees/quote', $params, $options);
    }
}
