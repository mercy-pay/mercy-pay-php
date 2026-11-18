<?php

declare(strict_types=1);

namespace MercyPay\Exception;

use MercyPay\MercyPayObject;

/**
 * The wait ended before the object reached a final status. Its last state is in $last.
 */
class WaitTimeoutException extends MercyPayException
{
    public function __construct(string $message, public readonly MercyPayObject $last)
    {
        parent::__construct($message);
    }
}
