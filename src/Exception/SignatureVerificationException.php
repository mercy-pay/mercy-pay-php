<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * A webhook whose signature is missing, wrong or too old: answer 400 and ignore it.
 */
class SignatureVerificationException extends MercyPayException {}
