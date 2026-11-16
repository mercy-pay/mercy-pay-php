<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * Une erreur de notre côté : la bibliothèque a déjà réessayé.
 */
class ApiException extends ApiErrorException {}
