<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;

final class AccountService extends AbstractService
{
    /**
     * Le compte : boutique, frais, limites, moyens de paiement.
     *
     * GET /api/v1/account
     */
    public function retrieve(): MercyPayObject
    {
        return $this->object('GET', 'account');
    }
}
