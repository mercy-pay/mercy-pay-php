<?php

declare(strict_types=1);

namespace MercyPay;

use ArrayAccess;
use JsonSerializable;
use LogicException;

/**
 * An object of the API (a payment, a payout, an event…). Read its fields as properties or as
 * array keys: $payment->status or $payment['status']. Nested objects are objects too; lists
 * stay lists. A field the API adds later simply appears, nothing breaks.
 *
 * @implements ArrayAccess<string, mixed>
 */
class MercyPayObject implements ArrayAccess, JsonSerializable
{
    /** @var array<string, mixed> */
    private array $values = [];

    /**
     * @param  array<string, mixed>  $values
     */
    final public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            $this->values[$key] = self::convert($value);
        }
    }

    /**
     * @param  array<string, mixed>  $values
     */
    public static function from(array $values): static
    {
        return new static($values);
    }

    public function __get(string $name): mixed
    {
        return $this->values[$name] ?? null;
    }

    public function __isset(string $name): bool
    {
        return isset($this->values[$name]);
    }

    public function offsetExists(mixed $offset): bool
    {
        return array_key_exists((string) $offset, $this->values);
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->values[(string) $offset] ?? null;
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        throw new LogicException('Les objets Mercy Pay sont en lecture seule.');
    }

    public function offsetUnset(mixed $offset): void
    {
        throw new LogicException('Les objets Mercy Pay sont en lecture seule.');
    }

    /**
     * Back to plain arrays, nested objects included.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return array_map(self::unconvert(...), $this->values);
    }

    /**
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    private static function convert(mixed $value): mixed
    {
        if (! is_array($value)) {
            return $value;
        }

        return array_is_list($value) ? array_map(self::convert(...), $value) : new MercyPayObject($value);
    }

    private static function unconvert(mixed $value): mixed
    {
        return match (true) {
            $value instanceof self => $value->toArray(),
            is_array($value) => array_map(self::unconvert(...), $value),
            default => $value,
        };
    }
}
