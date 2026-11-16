<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class PlanService extends AbstractService
{
    /**
     * Crée un plan d'abonnement.
     *
     * POST /api/v1/plans
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans', $params, $options);
    }

    /**
     * Les plans.
     *
     * GET /api/v1/plans
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('plans', $params);
    }

    /**
     * Un plan.
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
     * Modifie un plan.
     *
     * PATCH /api/v1/plans/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function update(string $id, array $params = []): MercyPayObject
    {
        return $this->object('PATCH', 'plans/'.self::id($id), $params);
    }

    /**
     * Supprime un plan jamais utilisé.
     *
     * DELETE /api/v1/plans/{id}
     */
    public function delete(string $id): MercyPayObject
    {
        return $this->object('DELETE', 'plans/'.self::id($id));
    }

    /**
     * Archive un plan.
     *
     * POST /api/v1/plans/{id}/archive
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function archive(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans/'.self::id($id).'/archive', [], $options);
    }

    /**
     * Réactive un plan archivé.
     *
     * POST /api/v1/plans/{id}/restore
     *
     * @param  array{idempotency_key?: string}  $options
     */
    public function restore(string $id, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'plans/'.self::id($id).'/restore', [], $options);
    }
}
