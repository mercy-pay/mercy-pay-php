<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * La clé d'idempotence est absente, déjà en cours ou réutilisée avec d'autres paramètres.
 */
class IdempotencyException extends ApiErrorException {}
