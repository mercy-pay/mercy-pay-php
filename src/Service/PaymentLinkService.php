<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PaymentLinkService extends AbstractService
{
    /**
     * Crée un lien de paiement.
     *
     * POST /api/v1/payment_links
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payment_links', $params, $options);
    }

    /**
     * Les liens de paiement.
     *
     * GET /api/v1/payment_links
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payment_links', $params);
    }

    /**
     * Un lien.
     *
     * GET /api/v1/payment_links/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'payment_links/'.self::id($id), $params);
    }

    /**
     * Modifie un lien.
     *
     * PATCH /api/v1/payment_links/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'payment_links/'.self::id($id), $params);
    }

    /**
     * Supprime un lien.
     *
     * DELETE /api/v1/payment_links/{id}
     */
    public function delete(string $id): MercyPayObject
    {
        return $this->object('DELETE', 'payment_links/'.self::id($id));
    }

    /**
     * Archive un lien.
     *
     * POST /api/v1/payment_links/{id}/archive
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function archive(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payment_links/'.self::id($id).'/archive', [], $options);
    }

    /**
     * Les paiements reçus par un lien.
     *
     * GET /api/v1/payment_links/{id}/payments
     *
     * @param  array<string, mixed>  $params
     */
    public function payments(string $id, array $params = []): Page
    {
        return $this->list('payment_links/'.self::id($id).'/payments', $params);
    }
}
