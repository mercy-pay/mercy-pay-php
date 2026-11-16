<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * Trop de requêtes : la bibliothèque a déjà réessayé, ralentissez.
 */
class RateLimitException extends ApiErrorException {}
