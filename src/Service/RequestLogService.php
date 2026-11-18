<?php

// Généré par scripts/generate.py depuis la référence de l'API. Ne pas modifier à la main.

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class RequestLogService extends AbstractService
{
    /**
     * Lister les requêtes.
     *
     * GET /api/v1/request_logs
     *
     * @param  array{status?: 'success'|'error', method?: 'GET'|'POST'|'PATCH'|'PUT'|'DELETE', path?: string, limit?: int, starting_after?: string, ending_before?: string}  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('request_logs', $params);
    }
}
