<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class CustomerService extends AbstractService
{
    /**
     * Crée un client.
     *
     * POST /api/v1/customers
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'customers', $params, $options);
    }

    /**
     * Les clients.
     *
     * GET /api/v1/customers
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('customers', $params);
    }

    /**
     * Un client.
     *
     * GET /api/v1/customers/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'customers/'.self::id($id), $params);
    }

    /**
     * Modifie un client.
     *
     * PATCH /api/v1/customers/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'customers/'.self::id($id), $params);
    }

    /**
     * Supprime un client.
     *
     * DELETE /api/v1/customers/{id}
     */
    public function delete(string $id): MercyPayObject
    {
        return $this->object('DELETE', 'customers/'.self::id($id));
    }

    /**
     * Les moyens de paiement enregistrés d'un client.
     *
     * GET /api/v1/customers/{id}/payment_methods
     *
     * @param  array<string, mixed>  $params
     */
    public function paymentMethods(string $customerId, array $params = []): Page
    {
        return $this->list('customers/'.self::id($customerId).'/payment_methods', $params);
    }

    /**
     * Choisit le moyen de paiement par défaut.
     *
     * POST /api/v1/customers/{id}/payment_methods/{id}/default
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function setDefaultPaymentMethod(string $customerId, string $paymentMethodId, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'customers/'.self::id($customerId).'/payment_methods/'.self::id($paymentMethodId).'/default', [], $options);
    }

    /**
     * Retire un moyen de paiement.
     *
     * DELETE /api/v1/customers/{id}/payment_methods/{id}
     */
    public function removePaymentMethod(string $customerId, string $paymentMethodId): MercyPayObject
    {
        return $this->object('DELETE', 'customers/'.self::id($customerId).'/payment_methods/'.self::id($paymentMethodId));
    }
}
