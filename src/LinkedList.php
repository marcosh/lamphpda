<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use function array_slice;

/**
 * @template-covariant A
 *
 * @psalm-immutable
 */
final class LinkedList
{
    /** @var bool */
    private $isEmpty;

    /** @var null|A */
    private $head;

    /** @var null|LinkedList<A> */
    private $tail;

    /**
     * @param null|A $head
     * @param null|LinkedList<A> $tail
     */
    private function __construct(bool $isEmpty, $head = null, ?self $tail = null)
    {
        $this->isEmpty = $isEmpty;
        $this->head = $head;
        $this->tail = $tail;
    }

    /**
     * @template B
     * @return LinkedList<B>
     *
     * @psalm-pure
     */
    public static function empty(): self
    {
        return new self(true);
    }

    /**
     * @template B
     * @param B $head
     * @param LinkedList<B> $tail
     * @return LinkedList<B>
     */
    public static function cons($head, self $tail): self
    {
        return new self(false, $head, $tail);
    }

    /**
     * @template B
     * @param list<B> $list
     * @return LinkedList<B>
     */
    public static function fromList(array $list): self
    {
        if (empty($list)) {
            return self::empty();
        }

        return self::cons($list[0], self::fromList(array_slice($list, 1)));
    }

    /**
     * @template B
     * @param callable(A, B): B $op
     * @param B $unit
     * @return B
     */
    public function foldr(callable $op, $unit)
    {
        if ($this->isEmpty) {
            return $unit;
        }

        /**
         * @psalm-suppress ImpureFunctionCall
         * @psalm-suppress PossiblyNullArgument
         * @psalm-suppress PossiblyNullReference
         */
        return $op($this->head, $this->tail->foldr($op, $unit));
    }
}
