<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class InvoiceService extends AbstractService
{
    /**
     * Lister les factures.
     *
     * GET /api/v1/invoices
     *
     * @param  array{status?: 'pending'|'paid'|'failed'|'canceled', subscription?: string, limit?: int, starting_after?: string, ending_before?: string, expand?: list<string>, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('invoices', $params);
    }

    /**
     * Lire une facture.
     *
     * GET /api/v1/invoices/{id}
     *
     * @param  array{expand?: list<string>}  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'invoices/'.self::id($id), $params);
    }

    /**
     * Encaisser une facture.
     *
     * POST /api/v1/invoices/{id}/pay
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function pay(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'invoices/'.self::id($id).'/pay', $params, $options);
    }

    /**
     * Envoyer la facture au client.
     *
     * POST /api/v1/invoices/{id}/send
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function send(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'invoices/'.self::id($id).'/send', $params, $options);
    }
}
