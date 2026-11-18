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
