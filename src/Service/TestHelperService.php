<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class TestHelperService extends AbstractService
{
    /**
     * Déclencher un événement de test.
     *
     * POST /api/v1/test_helpers/events
     *
     * @param  array{type: 'payment.succeeded'|'payment.failed'|'payment.refunded'|'payment.disputed'|'payment.dispute_closed'|'refund.succeeded'|'refund.failed'|'refund.canceled'|'payout.paid'|'payout.failed'|'payout.canceled'|'subscription.created'|'subscription.updated'|'subscription.past_due'|'subscription.unpaid'|'subscription.paused'|'subscription.resumed'|'subscription.canceled'|'invoice.created'|'invoice.upcoming'|'invoice.paid'|'invoice.canceled'|'invoice.payment_failed'|'checkout_session.completed'|'provider_status.changed'|'account.updated'}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function triggerEvent(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'test_helpers/events', $params, $options);
    }

    /**
     * Lister les numéros de test mobile money.
     *
     * GET /api/v1/test_helpers/mobile_money_numbers
     *
     * @param  array{country?: string, provider?: string}  $params
     */
    public function mobileMoneyNumbers(array $params = []): Page
    {
        return $this->list('test_helpers/mobile_money_numbers', $params);
    }

    /**
     * The sandbox number giving this outcome for this operator, or null when it has none.
     * The outcome is succeeded, pending, payout_failed, or a failure_code such as
     * insufficient_balance.
     *
     * ```php
     * $phone = $mercyPay->testHelpers->mobileMoneyNumber(Catalog::ORANGE_CMR, 'insufficient_balance');
     * ```
     */
    public function mobileMoneyNumber(string $provider, string $outcome = 'succeeded'): ?string
    {
        foreach ($this->mobileMoneyNumbers(['provider' => $provider])->data as $number) {
            $matches = $outcome === 'payout_failed'
                ? $number['applies_to'] === 'payout'
                : $number['applies_to'] === 'payment' && ($number['outcome'] === $outcome || $number['failure_code'] === $outcome);

            if ($matches && is_string($number['phone'])) {
                return $number['phone'];
            }
        }

        return null;
    }
}
