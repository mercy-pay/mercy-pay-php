<?php

declare(strict_types=1);

namespace MercyPay\Tests;

use MercyPay\Exception\ApiConnectionException;
use MercyPay\Exception\ApiException;
use MercyPay\Exception\InvalidRequestException;
use MercyPay\Exception\PaymentException;
use MercyPay\MercyPayClient;
use MercyPay\Tests\Support\FakeHttpClient;
use PHPUnit\Framework\TestCase;

final class ClientTest extends TestCase
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

    public function test_it_sends_the_key_and_unwraps_the_object(): void
    {
        $this->http->queue(FakeHttpClient::json(200, ['data' => ['id' => 'pay_1', 'status' => 'succeeded', 'payer' => ['name' => 'Awa']]]));

        $payment = $this->mercyPay->payments->retrieve('pay_1', ['expand' => ['checkout_session']]);

        self::assertSame('succeeded', $payment->status);
        self::assertSame('Awa', $payment->payer->name);
        self::assertSame('Awa', $payment['payer']['name']);
        self::assertSame(['id' => 'pay_1', 'status' => 'succeeded', 'payer' => ['name' => 'Awa']], $payment->toArray());

        $request = $this->http->requests[0];
        self::assertSame('GET', $request['method']);
        self::assertSame('https://api.test/api/v1/payments/pay_1?expand%5B0%5D=checkout_session', $request['url']);
        self::assertSame('Bearer mp_sandbox_test', $request['headers']['Authorization']);
        self::assertStringStartsWith('mercy-pay-php/', $request['headers']['User-Agent']);
        self::assertNull($request['body']);
    }

    public function test_a_post_sends_json_with_an_idempotency_key(): void
    {
        $this->mercyPay->checkoutSessions->create(['amount' => 15000, 'currency' => 'XAF', 'metadata' => ['order' => 'é/1']], ['idempotency_key' => 'order-4821']);

        $request = $this->http->requests[0];
        self::assertSame('{"amount":15000,"currency":"XAF","metadata":{"order":"é/1"}}', $request['body']);
        self::assertSame('order-4821', $request['headers']['Idempotency-Key']);
        self::assertSame('application/json', $request['headers']['Content-Type']);

        $this->mercyPay->refunds->cancel('re_1');
        self::assertSame('{}', $this->http->requests[1]['body']);
        self::assertStringStartsWith('php_', $this->http->requests[1]['headers']['Idempotency-Key']);
    }

    public function test_a_retry_reuses_the_same_idempotency_key(): void
    {
        $this->http->queue(
            new ApiConnectionException('timeout'),
            FakeHttpClient::json(502, []),
            FakeHttpClient::json(201, ['data' => ['id' => 'po_1']]),
        );

        $payout = $this->mercyPay->payouts->create(['amount' => 4000]);

        self::assertSame('po_1', $payout->id);
        self::assertCount(3, $this->http->requests);
        self::assertCount(1, array_unique(array_column(array_column($this->http->requests, 'headers'), 'Idempotency-Key')));
    }

    public function test_it_gives_up_after_the_retries(): void
    {
        $this->http->queue(FakeHttpClient::json(500, []), FakeHttpClient::json(500, []), FakeHttpClient::json(500, ['error' => ['type' => 'api_error', 'code' => 'server_error', 'message' => 'Erreur.']]));

        try {
            $this->mercyPay->account->retrieve();
            self::fail('expected an exception');
        } catch (ApiException $exception) {
            self::assertSame(500, $exception->getHttpStatus());
            self::assertCount(3, $this->http->requests);
        }
    }

    public function test_errors_carry_what_the_api_said(): void
    {
        $this->http->queue(FakeHttpClient::json(422, ['error' => [
            'type' => 'invalid_request_error', 'code' => 'validation_failed', 'message' => 'Montant invalide.',
            'param' => 'amount', 'request_id' => 'req_9', 'errors' => ['amount' => ['Montant invalide.']],
        ]]));

        try {
            $this->mercyPay->payments->create(['amount' => -1]);
            self::fail('expected an exception');
        } catch (InvalidRequestException $exception) {
            self::assertSame('validation_failed', $exception->getErrorCode());
            self::assertSame('amount', $exception->getParam());
            self::assertSame('req_9', $exception->getRequestId());
            self::assertSame(['amount' => ['Montant invalide.']], $exception->getErrors());
            self::assertCount(1, $this->http->requests, 'a 4xx is never retried');
        }

        $this->http->queue(FakeHttpClient::json(402, ['error' => ['type' => 'payment_error', 'code' => 'payment_declined', 'message' => 'Refusé.']]));
        $this->expectException(PaymentException::class);
        $this->mercyPay->payments->create(['amount' => 1]);
    }

    public function test_lists_page_through_every_object(): void
    {
        $this->http->queue(
            FakeHttpClient::json(200, ['object' => 'list', 'has_more' => true, 'next_cursor' => 'pay_2', 'data' => [['id' => 'pay_1'], ['id' => 'pay_2']]]),
            FakeHttpClient::json(200, ['object' => 'list', 'has_more' => false, 'next_cursor' => null, 'data' => [['id' => 'pay_3']]]),
        );

        $page = $this->mercyPay->payments->all(['limit' => 2, 'status' => 'succeeded']);
        self::assertCount(2, $page);

        $ids = [];
        foreach ($page->autoPagingIterator() as $payment) {
            $ids[] = $payment->id;
        }

        self::assertSame(['pay_1', 'pay_2', 'pay_3'], $ids);
        self::assertSame('https://api.test/api/v1/payments?limit=2&status=succeeded&starting_after=pay_2', $this->http->requests[1]['url']);
    }

    public function test_ids_are_escaped_in_the_path(): void
    {
        $this->mercyPay->customers->retrieve('cus_1/../x');

        self::assertSame('https://api.test/api/v1/customers/cus_1%2F..%2Fx', $this->http->requests[0]['url']);
    }
}
