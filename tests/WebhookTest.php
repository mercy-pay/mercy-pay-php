<?php

declare(strict_types=1);

namespace MercyPay\Tests;

use MercyPay\Exception\SignatureVerificationException;
use MercyPay\Webhook;
use PHPUnit\Framework\TestCase;

final class WebhookTest extends TestCase
{
    // The same vector as the Mercy Pay servers and every other official library.
    private const SECRET = 'whsec_test';

    private const PAYLOAD = '{"id":"evt_test","object":"event"}';

    private const TIMESTAMP = 1791022800;

    private const SIGNATURE = '52dafa4217ab4708f69eaeecb1f77e14f6405da36a477620c75328c30567e13b';

    public function test_a_valid_signature_gives_the_event(): void
    {
        $event = Webhook::constructEvent(self::PAYLOAD, 't='.self::TIMESTAMP.',v1='.self::SIGNATURE, self::SECRET, now: self::TIMESTAMP + 60);

        self::assertSame('evt_test', $event->id);
    }

    public function test_any_v1_may_match_after_a_rotation(): void
    {
        Webhook::verify(self::PAYLOAD, 't='.self::TIMESTAMP.',v1=deadbeef,v1='.self::SIGNATURE, self::SECRET, now: self::TIMESTAMP);
        $this->addToAssertionCount(1);
    }

    /**
     * @return array<string, array{string, ?string, int}>
     */
    public static function refused(): array
    {
        $header = 't='.self::TIMESTAMP.',v1='.self::SIGNATURE;

        return [
            'changed body' => [self::PAYLOAD.' ', $header, self::TIMESTAMP],
            'too old' => [self::PAYLOAD, $header, self::TIMESTAMP + 301],
            'missing header' => [self::PAYLOAD, null, self::TIMESTAMP],
            'no v1' => [self::PAYLOAD, 't='.self::TIMESTAMP, self::TIMESTAMP],
            'wrong secret' => [self::PAYLOAD, 't='.self::TIMESTAMP.',v1='.hash_hmac('sha256', self::TIMESTAMP.'.'.self::PAYLOAD, 'whsec_other'), self::TIMESTAMP],
        ];
    }

    #[\PHPUnit\Framework\Attributes\DataProvider('refused')]
    public function test_a_bad_webhook_is_refused(string $payload, ?string $header, int $now): void
    {
        $this->expectException(SignatureVerificationException::class);
        Webhook::constructEvent($payload, $header, self::SECRET, now: $now);
    }
}
