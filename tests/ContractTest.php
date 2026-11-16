<?php

declare(strict_types=1);

namespace MercyPay\Tests;

use MercyPay\MercyPayClient;
use MercyPay\Tests\Support\FakeHttpClient;
use PHPUnit\Framework\TestCase;

/**
 * Every operation of the API reference (tests/fixtures/openapi.json, a copy of
 * docs.mercy-pay.com/openapi.json) has a method, and that method calls the right verb and path.
 */
final class ContractTest extends TestCase
{
    public function test_every_operation_of_the_reference_has_a_method_calling_it(): void
    {
        $spec = json_decode((string) file_get_contents(__DIR__.'/fixtures/openapi.json'), true, flags: JSON_THROW_ON_ERROR);
        $operations = [];
        foreach (json_decode((string) file_get_contents(__DIR__.'/fixtures/operations.json'), true, flags: JSON_THROW_ON_ERROR) as $operation) {
            $operations[$operation['operationId']] = $operation;
        }

        $checked = 0;
        foreach ($spec['paths'] as $path => $methods) {
            foreach ($methods as $verb => $definition) {
                $operationId = $definition['operationId'];
                self::assertArrayHasKey($operationId, $operations, "Aucune méthode pour {$operationId}");
                $operation = $operations[$operationId];

                $http = new FakeHttpClient;
                $client = new MercyPayClient('mp_sandbox_test', ['base_url' => 'https://api.test', 'http_client' => $http]);
                $property = lcfirst(self::pluralService($operation['service']));
                $ids = array_map(fn (string $name): string => 'id_'.$name, $operation['ids']);

                $client->{$property}->{$operation['method']}(...$ids);

                $expectedPath = preg_replace_callback('/\{[^}]+\}/', function () use (&$ids): string {
                    return array_shift($ids);
                }, $path);

                self::assertSame(strtoupper($verb), $http->requests[0]['method'], $operationId);
                self::assertSame('https://api.test'.$expectedPath, strtok($http->requests[0]['url'], '?'), $operationId);
                $checked++;
            }
        }

        self::assertSame(count($operations), $checked, 'operations.json lists an operation the reference does not have');
    }

    private static function pluralService(string $service): string
    {
        return match ($service) {
            'Account', 'ProviderStatus', 'BillingPortal' => $service,
            default => $service.'s',
        };
    }
}
