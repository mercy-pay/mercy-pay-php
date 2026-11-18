<?php

declare(strict_types=1);

namespace MercyPay;

use ArrayIterator;
use Closure;
use Countable;
use Generator;
use IteratorAggregate;

/**
 * One page of a list, newest first. Loop over it for this page only, or over
 * autoPagingIterator() to go through every page, fetched as you go.
 *
 * @implements IteratorAggregate<int, MercyPayObject>
 */
final class Page implements Countable, IteratorAggregate
{
    /**
     * @param  list<MercyPayObject>  $data
     * @param  Closure(array<string, mixed>): Page  $fetch  fetches another page with these parameters
     * @param  array<string, mixed>  $params  the parameters this page was asked with
     */
    public function __construct(
        public readonly array $data,
        public readonly bool $hasMore,
        public readonly ?string $nextCursor,
        private readonly Closure $fetch,
        private readonly array $params = [],
    ) {}

    public function nextPage(): ?self
    {
        if (! $this->hasMore || $this->nextCursor === null) {
            return null;
        }

        $params = $this->params;
        unset($params['ending_before']);

        return ($this->fetch)([...$params, 'starting_after' => $this->nextCursor]);
    }

    /**
     * Every object of every page, newest first.
     *
     * @return Generator<int, MercyPayObject>
     */
    public function autoPagingIterator(): Generator
    {
        $page = $this;

        while ($page !== null) {
            foreach ($page->data as $object) {
                yield $object;
            }
            $page = $page->nextPage();
        }
    }

    /**
     * @return ArrayIterator<int, MercyPayObject>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }
}
