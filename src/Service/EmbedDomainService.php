<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class EmbedDomainService extends AbstractService
{
    /**
     * Ajouter un site.
     *
     * POST /api/v1/embed_domains
     *
     * @param  array{hostname: string}  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function create(array $params, array $options = []): MercyPayObject
    {
        return $this->object('POST', 'embed_domains', $params, $options);
    }

    /**
     * Lister les sites autorisés.
     *
     * GET /api/v1/embed_domains
     *
     * @param  array{limit?: int, starting_after?: string, ending_before?: string}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('embed_domains', $params);
    }

    /**
     * Lire un site autorisé.
     *
     * GET /api/v1/embed_domains/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function retrieve(string $id, array $params = []): MercyPayObject
    {
        return $this->object('GET', 'embed_domains/'.self::id($id), $params);
    }

    /**
     * Retirer un site.
     *
     * DELETE /api/v1/embed_domains/{id}
     *
     * @param  array<string, mixed>  $params
     */
    public function delete(string $id, array $params = []): MercyPayObject
    {
        return $this->object('DELETE', 'embed_domains/'.self::id($id), $params);
    }

    /**
     * Vérifier un site maintenant.
     *
     * POST /api/v1/embed_domains/{id}/verify
     *
     * @param  array<string, mixed>  $params
     * @param  array{idempotency_key?: string}  $options
     */
    public function verify(string $id, array $params = [], array $options = []): MercyPayObject
    {
        return $this->object('POST', 'embed_domains/'.self::id($id).'/verify', $params, $options);
    }
}
