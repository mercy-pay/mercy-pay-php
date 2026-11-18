<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class SubscriptionService extends AbstractService
{
    /**
     * Créer un abonnement.
     *
     * POST /api/v1/subscriptions
     *
     * @param  array{customer_name: string, customer_email: string, payment_method?: 'card'|'mobile_money', customer_phone?: string, customer_country?: string, customer_provider?: string, max_cycles?: int, metadata?: array<string, mixed>|null, plan: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions', $params, $options);
    }

    /**
     * Lister les abonnements.
     *
     * GET /api/v1/subscriptions
     *
     * @param  array{status?: 'incomplete'|'trialing'|'active'|'past_due'|'unpaid'|'paused'|'canceled', customer_email?: string, plan?: string, limit?: int, starting_after?: string, ending_before?: string, expand?: list<string>, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('subscriptions', $params);
    }

    /**
     * Lire un abonnement.
     *
     * GET /api/v1/subscriptions/{id}
     *
     * @param  array{expand?: list<string>}  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'subscriptions/'.self::id($id), $params);
    }

    /**
     * Modifier un abonnement.
     *
     * PATCH /api/v1/subscriptions/{id}
     *
     * @param  array{max_cycles?: int|null, customer_name?: string, customer_phone?: string, customer_country?: string, customer_provider?: string, metadata?: array<string, mixed>|null, plan?: string}  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'subscriptions/'.self::id($id), $params);
    }

    /**
     * Annuler un abonnement.
     *
     * POST /api/v1/subscriptions/{id}/cancel
     *
     * @param  array{at_period_end?: bool}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function cancel(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/cancel', $params, $options);
    }

    /**
     * Retirer une annulation prévue.
     *
     * POST /api/v1/subscriptions/{id}/reactivate
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function reactivate(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/reactivate', $params, $options);
    }

    /**
     * Passer à l'échéance (sandbox).
     *
     * POST /api/v1/subscriptions/{id}/advance
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function advance(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/advance', $params, $options);
    }

    /**
     * Mettre en pause.
     *
     * POST /api/v1/subscriptions/{id}/pause
     *
     * @param  array{resumes_at?: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function pause(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/pause', $params, $options);
    }

    /**
     * Reprendre.
     *
     * POST /api/v1/subscriptions/{id}/resume
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function resume(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'subscriptions/'.self::id($id).'/resume', $params, $options);
    }

    /**
     * Factures d'un abonnement.
     *
     * GET /api/v1/subscriptions/{id}/invoices
     *
     * @param  array{status?: 'pending'|'paid'|'failed'|'canceled', limit?: int, starting_after?: string, ending_before?: string, expand?: list<string>, created?: array{gte?: string, lte?: string}}  $params
     */
    public function invoices(string $id, array $params = []): Page
    {
        return $this->list('subscriptions/'.self::id($id).'/invoices', $params);
    }
}
