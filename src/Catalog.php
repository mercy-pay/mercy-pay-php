<?php

// Généré par scripts/generate.py depuis le catalogue Mercy Pay. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay;

/**
 * The open countries, their mobile money operators and the webhook event types. A new
 * operator may appear before this list knows it: any code stays accepted.
 */
final class Catalog
{
    /** @var array<string, array{name: string, currency: string, providers: array<string, string>}> */
    public const COUNTRIES = [
        'CMR' => ['name' => 'Cameroun', 'currency' => 'XAF', 'providers' => ['MTN_MOMO_CMR' => 'MTN Mobile Money', 'ORANGE_CMR' => 'Orange Money']],
        'COG' => ['name' => 'Congo', 'currency' => 'XAF', 'providers' => ['MTN_MOMO_COG' => 'MTN Mobile Money', 'AIRTEL_COG' => 'Airtel Money']],
        'GAB' => ['name' => 'Gabon', 'currency' => 'XAF', 'providers' => ['AIRTEL_GAB' => 'Airtel Money', 'MOOV_GAB' => 'Moov Money']],
        'BEN' => ['name' => 'Bénin', 'currency' => 'XOF', 'providers' => ['MTN_MOMO_BEN' => 'MTN Mobile Money', 'MOOV_BEN' => 'Moov Money']],
        'CIV' => ['name' => 'Côte d\'Ivoire', 'currency' => 'XOF', 'providers' => ['MTN_MOMO_CIV' => 'MTN Mobile Money', 'ORANGE_CIV' => 'Orange Money', 'MOOV_CIV' => 'Moov Money']],
        'SEN' => ['name' => 'Sénégal', 'currency' => 'XOF', 'providers' => ['FREE_SEN' => 'Free Money', 'ORANGE_SEN' => 'Orange Money']],
        'COD' => ['name' => 'RD Congo', 'currency' => 'CDF', 'providers' => ['VODACOM_MPESA_COD' => 'M-Pesa', 'AIRTEL_COD' => 'Airtel Money', 'ORANGE_COD' => 'Orange Money']],
        'RWA' => ['name' => 'Rwanda', 'currency' => 'RWF', 'providers' => ['AIRTEL_RWA' => 'Airtel Money', 'MTN_MOMO_RWA' => 'MTN Mobile Money']],
        'SLE' => ['name' => 'Sierra Leone', 'currency' => 'SLE', 'providers' => ['ORANGE_SLE' => 'Orange Money']],
        'UGA' => ['name' => 'Ouganda', 'currency' => 'UGX', 'providers' => ['AIRTEL_OAPI_UGA' => 'Airtel Money', 'MTN_MOMO_UGA' => 'MTN Mobile Money']],
        'ZMB' => ['name' => 'Zambie', 'currency' => 'ZMW', 'providers' => ['MTN_MOMO_ZMB' => 'MTN Mobile Money', 'AIRTEL_OAPI_ZMB' => 'Airtel Money', 'ZAMTEL_ZMB' => 'Zamtel Money']],
        'KEN' => ['name' => 'Kenya', 'currency' => 'KES', 'providers' => ['MPESA_KEN' => 'M-Pesa']],
        'MOZ' => ['name' => 'Mozambique', 'currency' => 'MZN', 'providers' => ['VODACOM_MOZ' => 'M-Pesa']],
    ];

    /** @var list<string> */
    public const EVENT_TYPES = ['payment.succeeded', 'payment.failed', 'payment.refunded', 'payment.disputed', 'payment.dispute_closed', 'refund.succeeded', 'refund.failed', 'refund.canceled', 'payout.paid', 'payout.failed', 'payout.canceled', 'subscription.created', 'subscription.updated', 'subscription.past_due', 'subscription.unpaid', 'subscription.paused', 'subscription.resumed', 'subscription.canceled', 'invoice.created', 'invoice.upcoming', 'invoice.paid', 'invoice.canceled', 'invoice.payment_failed', 'checkout_session.completed', 'provider_status.changed', 'account.updated'];

    public const MTN_MOMO_CMR = 'MTN_MOMO_CMR';
    public const ORANGE_CMR = 'ORANGE_CMR';
    public const MTN_MOMO_COG = 'MTN_MOMO_COG';
    public const AIRTEL_COG = 'AIRTEL_COG';
    public const AIRTEL_GAB = 'AIRTEL_GAB';
    public const MOOV_GAB = 'MOOV_GAB';
    public const MTN_MOMO_BEN = 'MTN_MOMO_BEN';
    public const MOOV_BEN = 'MOOV_BEN';
    public const MTN_MOMO_CIV = 'MTN_MOMO_CIV';
    public const ORANGE_CIV = 'ORANGE_CIV';
    public const MOOV_CIV = 'MOOV_CIV';
    public const FREE_SEN = 'FREE_SEN';
    public const ORANGE_SEN = 'ORANGE_SEN';
    public const VODACOM_MPESA_COD = 'VODACOM_MPESA_COD';
    public const AIRTEL_COD = 'AIRTEL_COD';
    public const ORANGE_COD = 'ORANGE_COD';
    public const AIRTEL_RWA = 'AIRTEL_RWA';
    public const MTN_MOMO_RWA = 'MTN_MOMO_RWA';
    public const ORANGE_SLE = 'ORANGE_SLE';
    public const AIRTEL_OAPI_UGA = 'AIRTEL_OAPI_UGA';
    public const MTN_MOMO_UGA = 'MTN_MOMO_UGA';
    public const MTN_MOMO_ZMB = 'MTN_MOMO_ZMB';
    public const AIRTEL_OAPI_ZMB = 'AIRTEL_OAPI_ZMB';
    public const ZAMTEL_ZMB = 'ZAMTEL_ZMB';
    public const MPESA_KEN = 'MPESA_KEN';
    public const VODACOM_MOZ = 'VODACOM_MOZ';
}
