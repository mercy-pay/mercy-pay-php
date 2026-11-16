<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class InvoiceService extends AbstractService
{
    /**
     * Les factures.
     *
     * GET /api/v1/invoices
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('invoices', $params);
    }

    /**
     * Une facture.
     *
     * GET /api/v1/invoices/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'invoices/'.self::id($id), $params);
    }

    /**
     * Encaisse une facture en attente.
     *
     * POST /api/v1/invoices/{id}/pay
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function pay(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'invoices/'.self::id($id).'/pay', [], $options);
    }

    /**
     * Envoie la facture au client par e-mail.
     *
     * POST /api/v1/invoices/{id}/send
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function send(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'invoices/'.self::id($id).'/send', [], $options);
    }
}
