<?php

declare(strict_types=1);

namespace MercyPay;

use JsonException;
use MercyPay\Exception\SignatureVerificationException;

/**
 * Checks a webhook before trusting it: the MercyPay-Signature header must be an HMAC-SHA256
 * of "{t}.{raw body}" with your endpoint's secret, and t must be recent.
 */
final class Webhook
{
    public const DEFAULT_TOLERANCE = 300;

    /**
     * Verifies the signature, then returns the event. Pass the raw body exactly as received,
     * before any JSON decoding.
     *
     * @throws SignatureVerificationException
     */
    public static function constructEvent(string $payload, ?string $signatureHeader, string $secret, int $tolerance = self::DEFAULT_TOLERANCE, ?int $now = null): MercyPayObject
    {
        self::verify($payload, $signatureHeader, $secret, $tolerance, $now);

        try {
            $event = json_decode($payload, true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw new SignatureVerificationException("Le corps du webhook n'est pas du JSON valide.");
        }

        if (! is_array($event)) {
            throw new SignatureVerificationException("Le corps du webhook n'est pas un événement.");
        }

        return MercyPayObject::from($event);
    }

    /**
     * @throws SignatureVerificationException
     */
    public static function verify(string $payload, ?string $signatureHeader, string $secret, int $tolerance = self::DEFAULT_TOLERANCE, ?int $now = null): void
    {
        if ($signatureHeader === null || $signatureHeader === '') {
            throw new SignatureVerificationException('En-tête MercyPay-Signature absent.');
        }

        $timestamp = null;
        $signatures = [];

        foreach (explode(',', $signatureHeader) as $part) {
            [$key, $value] = array_pad(explode('=', trim($part), 2), 2, '');

            if ($key === 't' && ctype_digit($value)) {
                $timestamp = (int) $value;
            } elseif ($key === 'v1' && $value !== '') {
                $signatures[] = $value;
            }
        }

        if ($timestamp === null || $signatures === []) {
            throw new SignatureVerificationException('En-tête MercyPay-Signature mal formé.');
        }

        if (abs(($now ?? time()) - $timestamp) > $tolerance) {
            throw new SignatureVerificationException('Signature trop ancienne : le webhook a peut-être été rejoué.');
        }

        $expected = hash_hmac('sha256', $timestamp.'.'.$payload, $secret);

        foreach ($signatures as $signature) {
            if (hash_equals($expected, $signature)) {
                return;
            }
        }

        throw new SignatureVerificationException('Signature invalide : vérifiez le secret de votre endpoint.');
    }
}
