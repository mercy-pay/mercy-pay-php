<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * No answer from Mercy Pay (network, timeout). The request may or may not have been taken
 * into account: send it again with the same idempotency key, never a new one.
 */
class ApiConnectionException extends MercyPayException {}
