use MercyPay\Exception\WaitTimeoutException;

    /**
     * Reads the session until it is completed, expired or cancelled.
     *
     * @param  array{timeout?: int, interval?: int}  $options  milliseconds: 120 000 and 2 000 by default
     *
     * @throws WaitTimeoutException with the last state, when the timeout passes first
     */
    public function waitForCompletion(string $id, array $options = []): MercyPayObject
    {
        return $this->poll(
            fn (): MercyPayObject => $this->retrieve($id),
            ['completed', 'expired', 'cancelled'],
            $options,
            "La session {$id}",
        );
    }
