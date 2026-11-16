<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;

final class TestHelperService extends AbstractService
{
    /**
     * Sandbox : déclenche un événement avec des données d'exemple. Passez ['type' => 'payment.succeeded'].
     *
     * POST /api/v1/test_helpers/events
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function triggerEvent(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'test_helpers/events', $params, $options);
    }
}
