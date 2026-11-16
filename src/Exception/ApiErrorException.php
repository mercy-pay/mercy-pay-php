<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * An error answered by the API, with everything it said about it. Quote getRequestId() to
 * the Mercy Pay support: it finds the exact call.
 */
class ApiErrorException extends MercyPayException
{
    /**
     * @param  array<string, list<string>>  $errors
     * @param  array<string, mixed>  $body
     */
    final public function __construct(
        string $message,
        private readonly int $httpStatus,
        private readonly ?string $errorType,
        private readonly ?string $errorCode,
        private readonly ?string $param,
        private readonly ?string $requestId,
        private readonly array $errors = [],
        private readonly array $body = [],
    ) {
        parent::__construct($message, $httpStatus);
    }

    /**
     * The exception matching the error family the API answered.
     *
     * @param  array<string, mixed>  $body
     */
    public static function fromResponse(int $status, array $body, ?string $requestId): self
    {
        $error = is_array($body['error'] ?? null) ? $body['error'] : [];
        $type = isset($error['type']) ? (string) $error['type'] : null;
        $class = match (true) {
            $type === 'authentication_error' || $status === 401 => AuthenticationException::class,
            $type === 'rate_limit_error' || $status === 429 => RateLimitException::class,
            $type === 'idempotency_error' => IdempotencyException::class,
            $type === 'payment_error' => PaymentException::class,
            $status >= 500 || $type === 'api_error' => ApiException::class,
            default => InvalidRequestException::class,
        };

        return new $class(
            (string) ($error['message'] ?? "Mercy Pay a répondu {$status}."),
            $status,
            $type,
            isset($error['code']) ? (string) $error['code'] : null,
            isset($error['param']) ? (string) $error['param'] : null,
            isset($error['request_id']) ? (string) $error['request_id'] : $requestId,
            is_array($error['errors'] ?? null) ? $error['errors'] : [],
            $body,
        );
    }

    public function getHttpStatus(): int
    {
        return $this->httpStatus;
    }

    /** The error family: invalid_request_error, payment_error, authentication_error… */
    public function getErrorType(): ?string
    {
        return $this->errorType;
    }

    /** The precise reason, stable over time: validation_failed, payment_declined… */
    public function getErrorCode(): ?string
    {
        return $this->errorCode;
    }

    /** The field the error is about, when there is one. */
    public function getParam(): ?string
    {
        return $this->param;
    }

    public function getRequestId(): ?string
    {
        return $this->requestId;
    }

    /**
     * Every refused field with its messages.
     *
     * @return array<string, list<string>>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * @return array<string, mixed>
     */
    public function getBody(): array
    {
        return $this->body;
    }
}
