<?php

declare(strict_types=1);

namespace MercyPay\Tests;

use MercyPay\Catalog;
use MercyPay\Exception\WaitTimeoutException;
use MercyPay\HttpClient\Response;
use MercyPay\MercyPayClient;
use MercyPay\Tests\Support\FakeHttpClient;
use PHPUnit\Framework\TestCase;

final class HelpersTest extends TestCase
{
    private FakeHttpClient $http;

    private MercyPayClient $mercyPay;

    protected function setUp(): void
    {
        $this->http = new FakeHttpClient;
        $this->mercyPay = new MercyPayClient('mp_sandbox_test', [
            'base_url' => 'https://api.test',
            'http_client' => $this->http,
            'sleep' => static fn (int $milliseconds) => null,
        ]);
    }

    private static function payment(string $status): Response
    {
        return FakeHttpClient::json(200, ['data' => ['id' => 'pay_1', 'object' => 'payment', 'status' => $status]]);
    }

    public function test_it_reads_the_payment_again_until_its_status_is_final(): void
    {
        $this->http->queue(self::payment('pending'), self::payment('processing'), self::payment('succeeded'));

        self::assertSame('succeeded', $this->mercyPay->payments->waitForFinalStatus('pay_1')->status);
        self::assertCount(3, $this->http->requests);
    }

    public function test_it_gives_up_after_the_timeout_with_the_last_state(): void
    {
        $this->http->queue(self::payment('pending'), self::payment('pending'), self::payment('pending'));

        try {
            $this->mercyPay->payments->waitForFinalStatus('pay_1', ['timeout' => 1_000, 'interval' => 600]);
            self::fail('No timeout.');
        } catch (WaitTimeoutException $exception) {
            self::assertSame('pending', $exception->last->status);
        }
    }

    public function test_it_picks_the_sandbox_number_for_an_outcome_or_a_failure_code(): void
    {
        $numbers = FakeHttpClient::json(200, ['object' => 'list', 'has_more' => false, 'data' => [
            ['provider' => 'ORANGE_CMR', 'phone' => '237693456789', 'applies_to' => 'payment', 'outcome' => 'succeeded', 'failure_code' => null],
            ['provider' => 'ORANGE_CMR', 'phone' => '237693456049', 'applies_to' => 'payment', 'outcome' => 'failed', 'failure_code' => 'insufficient_balance'],
            ['provider' => 'ORANGE_CMR', 'phone' => '237693456119', 'applies_to' => 'payout', 'outcome' => 'failed', 'failure_code' => null],
        ]]);
        $this->http->queue($numbers, $numbers, $numbers, $numbers);

        self::assertSame('237693456789', $this->mercyPay->testHelpers->mobileMoneyNumber(Catalog::ORANGE_CMR));
        self::assertSame('237693456049', $this->mercyPay->testHelpers->mobileMoneyNumber('ORANGE_CMR', 'insufficient_balance'));
        self::assertSame('237693456119', $this->mercyPay->testHelpers->mobileMoneyNumber('ORANGE_CMR', 'payout_failed'));
        self::assertNull($this->mercyPay->testHelpers->mobileMoneyNumber('ORANGE_CMR', 'payer_not_found'));
        self::assertSame('https://api.test/api/v1/test_helpers/mobile_money_numbers?provider=ORANGE_CMR', $this->http->requests[0]['url']);
    }

    public function test_the_catalog_lists_countries_operators_and_event_types(): void
    {
        self::assertArrayHasKey('MTN_MOMO_CMR', Catalog::COUNTRIES['CMR']['providers']);
        self::assertContains('payment.succeeded', Catalog::EVENT_TYPES);
    }
}
