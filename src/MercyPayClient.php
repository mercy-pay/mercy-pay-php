<?php

declare(strict_types=1);

namespace MercyPay;

use InvalidArgumentException;
use JsonException;
use MercyPay\Exception\ApiConnectionException;
use MercyPay\Exception\ApiErrorException;
use MercyPay\HttpClient\ClientInterface;
use MercyPay\HttpClient\CurlClient;
use MercyPay\HttpClient\Response;
use MercyPay\Service\AbstractService;
use MercyPay\Service\AccountService;
use MercyPay\Service\BillingPortalService;
use MercyPay\Service\CheckoutSessionService;
use MercyPay\Service\CustomerService;
use MercyPay\Service\EventService;
use MercyPay\Service\FeeService;
use MercyPay\Service\InvoiceService;
use MercyPay\Service\PaymentLinkService;
use MercyPay\Service\PaymentService;
use MercyPay\Service\PayoutService;
use MercyPay\Service\PlanService;
use MercyPay\Service\ProviderStatusService;
use MercyPay\Service\RefundService;
use MercyPay\Service\RequestLogService;
use MercyPay\Service\SubscriptionService;
use MercyPay\Service\TestHelperService;
use MercyPay\Service\WalletService;
use MercyPay\Service\WalletTransferService;
use MercyPay\Service\WebhookEndpointService;

/**
 * The entry point: one client per API key.
 *
 *     $mercyPay = new MercyPayClient('mp_sandbox_…');
 *     $session = $mercyPay->checkoutSessions->create(['amount' => 15000, 'currency' => 'XAF']);
 *
 * Options: base_url, timeout (seconds, 30), max_retries (2), http_client (a ClientInterface).
 * A network error, a 429 or a 5xx is retried; a POST always carries an Idempotency-Key, the
 * same on every attempt, so a retry never creates twice.
 */
final class MercyPayClient
{
    public const VERSION = '0.1.0';

    public const DEFAULT_BASE_URL = 'https://mercy-pay.com';

    public readonly AccountService $account;

    public readonly BillingPortalService $billingPortal;

    public readonly CheckoutSessionService $checkoutSessions;

    public readonly CustomerService $customers;

    public readonly EventService $events;

    public readonly FeeService $fees;

    public readonly InvoiceService $invoices;

    public readonly PaymentLinkService $paymentLinks;

    public readonly PaymentService $payments;

    public readonly PayoutService $payouts;

    public readonly PlanService $plans;

    public readonly ProviderStatusService $providerStatus;

    public readonly RefundService $refunds;

    public readonly RequestLogService $requestLogs;

    public readonly SubscriptionService $subscriptions;

    public readonly TestHelperService $testHelpers;

    public readonly WalletService $wallets;

    public readonly WalletTransferService $walletTransfers;

    public readonly WebhookEndpointService $webhookEndpoints;

    private readonly string $baseUrl;

    private readonly int $timeout;

    private readonly int $maxRetries;

    private readonly ClientInterface $httpClient;

    /** @var callable(int): void */
    private $sleep;

    /**
     * @param  array{base_url?: string, timeout?: int, max_retries?: int, http_client?: ClientInterface, sleep?: callable(int): void}  $options
     */
    public function __construct(private readonly string $apiKey, array $options = [])
    {
        if ($apiKey === '') {
            throw new InvalidArgumentException('Clé API manquante : passez votre clé mp_sandbox_… ou mp_live_….');
        }

        $this->baseUrl = rtrim($options['base_url'] ?? self::DEFAULT_BASE_URL, '/');
        $this->timeout = $options['timeout'] ?? 30;
        $this->maxRetries = max(0, $options['max_retries'] ?? 2);
        $this->httpClient = $options['http_client'] ?? new CurlClient;
        $this->sleep = $options['sleep'] ?? static fn (int $milliseconds) => usleep($milliseconds * 1000);

        $this->account = new AccountService($this);
        $this->billingPortal = new BillingPortalService($this);
        $this->checkoutSessions = new CheckoutSessionService($this);
        $this->customers = new CustomerService($this);
        $this->events = new EventService($this);
        $this->fees = new FeeService($this);
        $this->invoices = new InvoiceService($this);
        $this->paymentLinks = new PaymentLinkService($this);
        $this->payments = new PaymentService($this);
        $this->payouts = new PayoutService($this);
        $this->plans = new PlanService($this);
        $this->providerStatus = new ProviderStatusService($this);
        $this->refunds = new RefundService($this);
        $this->requestLogs = new RequestLogService($this);
        $this->subscriptions = new SubscriptionService($this);
        $this->testHelpers = new TestHelperService($this);
        $this->wallets = new WalletService($this);
        $this->walletTransfers = new WalletTransferService($this);
        $this->webhookEndpoints = new WebhookEndpointService($this);
    }

    /**
     * The services as methods too, $mercyPay->payments() as well as $mercyPay->payments: what a
     * Laravel facade needs (MercyPay::payments()->create(…)).
     *
     * @param  array<int, mixed>  $arguments
     */
    public function __call(string $name, array $arguments): AbstractService
    {
        if (property_exists($this, $name) && $this->{$name} instanceof AbstractService) {
            return $this->{$name};
        }

        throw new \BadMethodCallException("MercyPayClient n'a pas de service {$name}.");
    }

    /**
     * Sends one call and returns the decoded body. Used by the services; call it yourself for
     * an endpoint this version does not know yet.
     *
     * @param  array<string, mixed>  $params  query string for GET and DELETE, JSON body otherwise
     * @param  array{idempotency_key?: string}  $options
     * @return array<string, mixed>
     *
     * @throws ApiErrorException when the API answers an error
     * @throws ApiConnectionException when it cannot be reached
     */
    public function request(string $method, string $path, array $params = [], array $options = []): array
    {
        $method = strtoupper($method);
        $url = $this->baseUrl.'/api/v1/'.ltrim($path, '/');
        $body = null;

        $headers = [
            'Authorization' => 'Bearer '.$this->apiKey,
            'Accept' => 'application/json',
            'User-Agent' => 'mercy-pay-php/'.self::VERSION.' PHP/'.PHP_VERSION,
        ];

        if (in_array($method, ['GET', 'DELETE'], true)) {
            if ($params !== []) {
                $url .= '?'.http_build_query($params, '', '&', PHP_QUERY_RFC3986);
            }
        } else {
            $body = json_encode($params === [] ? new \stdClass : $params, JSON_THROW_ON_ERROR | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $headers['Content-Type'] = 'application/json';
        }

        if ($method === 'POST') {
            $headers['Idempotency-Key'] = $options['idempotency_key'] ?? 'php_'.bin2hex(random_bytes(16));
        }

        $response = $this->sendWithRetries($method, $url, $headers, $body);

        if ($response->body === '') {
            $decoded = [];
        } else {
            try {
                $decoded = json_decode($response->body, true, 512, JSON_THROW_ON_ERROR);
            } catch (JsonException) {
                $decoded = [];
            }
        }
        $decoded = is_array($decoded) ? $decoded : [];

        if ($response->status >= 400) {
            throw ApiErrorException::fromResponse($response->status, $decoded, $response->header('Request-Id'));
        }

        return $decoded;
    }

    /**
     * @param  array<string, string>  $headers
     */
    private function sendWithRetries(string $method, string $url, array $headers, ?string $body): Response
    {
        $attempt = 0;

        while (true) {
            try {
                $response = $this->httpClient->send($method, $url, $headers, $body, $this->timeout);
            } catch (ApiConnectionException $exception) {
                if ($attempt >= $this->maxRetries) {
                    throw $exception;
                }
                ($this->sleep)($this->backoff(++$attempt, null));

                continue;
            }

            $retryable = $response->status === 429 || $response->status >= 500;

            if (! $retryable || $attempt >= $this->maxRetries) {
                return $response;
            }

            ($this->sleep)($this->backoff(++$attempt, $response->header('Retry-After')));
        }
    }

    /** Milliseconds before the next attempt: what Retry-After says, otherwise 0.5 s, 1 s, 2 s… */
    private function backoff(int $attempt, ?string $retryAfter): int
    {
        if ($retryAfter !== null && ctype_digit($retryAfter)) {
            return min((int) $retryAfter, 60) * 1000;
        }

        return 500 * (2 ** ($attempt - 1));
    }
}
