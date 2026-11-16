<?php

declare(strict_types=1);

namespace MercyPay\Exception;

/**
 * Le paiement a été refusé dès sa création : proposez un autre moyen au payeur.
 */
class PaymentException extends ApiErrorException {}
