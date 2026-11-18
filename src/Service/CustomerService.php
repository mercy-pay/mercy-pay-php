<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class CustomerService extends AbstractService
{
    /**
     * Créer un client.
     *
     * POST /api/v1/customers
     *
     * @param  array{email: string, name?: string|null, metadata?: array<string, mixed>}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'customers', $params, $options);
    }

    /**
     * Lister les clients.
     *
     * GET /api/v1/customers
     *
     * @param  array{email?: string, limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('customers', $params);
    }

    /**
     * Lire un client.
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
     * Modifier un client.
     *
     * PATCH /api/v1/customers/{id}
     *
     * @param  array{email?: string, name?: string|null, metadata?: array<string, mixed>}  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'customers/'.self::id($id), $params);
    }

    /**
     * Supprimer un client.
     *
     * DELETE /api/v1/customers/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function delete(string $id, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'customers/'.self::id($id), $params);
    }

    /**
     * Moyens de paiement d'un client.
     *
     * GET /api/v1/customers/{customer}/payment_methods
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function paymentMethods(string $customerId, array $params = []): Page
    {
        return $this->list('customers/'.self::id($customerId).'/payment_methods', $params);
    }

    /**
     * Choisir le moyen par défaut.
     *
     * POST /api/v1/customers/{customer}/payment_methods/{payment_method}/default
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function setDefaultPaymentMethod(string $customerId, string $paymentMethodId, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'customers/'.self::id($customerId).'/payment_methods/'.self::id($paymentMethodId).'/default', $params, $options);
    }

    /**
     * Retirer un moyen de paiement.
     *
     * DELETE /api/v1/customers/{customer}/payment_methods/{payment_method}
     *
     * @param  array<string, mixed>  $params
     */
    public function removePaymentMethod(string $customerId, string $paymentMethodId, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'customers/'.self::id($customerId).'/payment_methods/'.self::id($paymentMethodId), $params);
    }
}
