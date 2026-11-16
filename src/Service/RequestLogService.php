<?php

declare(strict_types=1);

namespace MercyPay\Service;

use MercyPay\MercyPayObject;
use MercyPay\Page;

final class RequestLogService extends AbstractService
{
    /**
     * Les appels faits avec vos clés.
     *
     * GET /api/v1/request_logs
     *
     * @param  array<string, mixed>  $params
     */
    public function all(array $params = []): Page
    {
        return $this->list('request_logs', $params);
    }
}
