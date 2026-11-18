use MercyPay\Exception\WaitTimeoutException;

    /**
     * Reads the payment until it is final (succeeded, failed, canceled, expired…), for a
     * script or a test. On a server, prefer the payment.succeeded webhook.
     *
     * @param  array{timeout?: int, interval?: int}  $options  milliseconds: 120 000 and 2 000 by default
     *
     * @throws WaitTimeoutException with the last state, when the timeout passes first
     */
    public function waitForFinalStatus(string $id, array $options = []): MercyPayObject
    {
        return $this->poll(
            fn (): MercyPayObject => $this->retrieve($id),
            ['succeeded', 'failed', 'canceled', 'expired', 'refunded', 'disputed'],
            $options,
            "Le paiement {$id}",
        );
    }
