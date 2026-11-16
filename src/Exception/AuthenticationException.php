<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * La clé API est absente, invalide ou sans droit sur cette action.
 */
class AuthenticationException extends ApiErrorException {}
