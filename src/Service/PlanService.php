<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PlanService extends AbstractService
{
    /**
     * Créer un plan.
     *
     * POST /api/v1/plans
     *
     * @param  array{name: string, amount: int, currency: string, interval: 'daily'|'weekly'|'monthly'|'yearly', interval_count?: int, trial_period_days?: int, description?: string, portal_switchable?: bool, metadata?: array<string, mixed>|null}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans', $params, $options);
    }

    /**
     * Lister les plans.
     *
     * GET /api/v1/plans
     *
     * @param  array{status?: 'active'|'archived', limit?: int, starting_after?: string, ending_before?: string, created?: array{gte?: string, lte?: string}}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('plans', $params);
    }

    /**
     * Lire un plan.
     *
     * GET /api/v1/plans/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'plans/'.self::id($id), $params);
    }

    /**
     * Modifier un plan.
     *
     * PATCH /api/v1/plans/{id}
     *
     * @param  array{name?: string, description?: string|null, portal_switchable?: bool, metadata?: array<string, mixed>|null}  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'plans/'.self::id($id), $params);
    }

    /**
     * Supprimer un plan.
     *
     * DELETE /api/v1/plans/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function delete(string $id, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'plans/'.self::id($id), $params);
    }

    /**
     * Archiver un plan.
     *
     * POST /api/v1/plans/{id}/archive
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function archive(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans/'.self::id($id).'/archive', $params, $options);
    }

    /**
     * Réactiver un plan.
     *
     * POST /api/v1/plans/{id}/restore
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function restore(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans/'.self::id($id).'/restore', $params, $options);
    }
}
