<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PaymentLinkService extends AbstractService
{
    /**
     * Créer un lien.
     *
     * POST /api/v1/payment_links
     *
     * @param  array{title: string, description?: string, pricing_mode?: 'fixed'|'flexible'|'options'|'cart', amount?: int, minimum_amount?: int, price_options?: list<array{id?: int, label: string, amount: int}>, items?: list<array{id?: int, name: string, description?: string, unit_amount: int, min_quantity?: int, max_quantity?: int, variants?: list<array{id?: int, label: string, unit_amount?: int}>}>, custom_fields_schema?: list<array<string, mixed>>, collect_phone?: bool, collect_address?: bool, after_payment_message?: string, max_uses?: int, expires_at?: string, success_url?: string, cancel_url?: string, active?: bool, metadata?: array<string, mixed>|null, type?: 'one_time'|'recurring', currency?: string, plan?: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payment_links', $params, $options);
    }

    /**
     * Lister les liens.
     *
     * GET /api/v1/payment_links
     *
     * @param  array{active?: bool, type?: 'one_time'|'recurring', archived?: bool, limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('payment_links', $params);
    }

    /**
     * Lire un lien.
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
     * Modifier un lien.
     *
     * PATCH /api/v1/payment_links/{id}
     *
     * @param  array{title?: string, description?: string, pricing_mode?: 'fixed'|'flexible'|'options'|'cart', amount?: int, minimum_amount?: int, price_options?: list<array{id?: int, label: string, amount: int}>, items?: list<array{id?: int, name: string, description?: string, unit_amount: int, min_quantity?: int, max_quantity?: int, variants?: list<array{id?: int, label: string, unit_amount?: int}>}>, custom_fields_schema?: list<array<string, mixed>>, collect_phone?: bool, collect_address?: bool, after_payment_message?: string, max_uses?: int, expires_at?: string, success_url?: string, cancel_url?: string, active?: bool, metadata?: array<string, mixed>|null}  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'payment_links/'.self::id($id), $params);
    }

    /**
     * Supprimer un lien.
     *
     * DELETE /api/v1/payment_links/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function delete(string $id, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'payment_links/'.self::id($id), $params);
    }

    /**
     * Archiver un lien.
     *
     * POST /api/v1/payment_links/{id}/archive
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function archive(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'payment_links/'.self::id($id).'/archive', $params, $options);
    }

    /**
     * Paiements d'un lien.
     *
     * GET /api/v1/payment_links/{id}/payments
     *
     * @param  array{status?: string, limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function payments(string $id, array $params = []): Page
    {
        return $this->list('payment_links/'.self::id($id).'/payments', $params);
    }
}
