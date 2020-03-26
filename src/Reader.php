<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * @template R
 * @template A
 * @implements Functor<A>
 * @psalm-immutable
 */
final class Reader implements Functor
{
    /**
     * @var callable
     * @psalm-var callable(R): A
     */
    private $runReader;

    /**
     * @param callable $runReader
     * @psalm-param callable(R): A $runReader
     * @psalm-pure
     */
    private function __construct(callable $runReader)
    {
        $this->runReader = $runReader;
    }

    /**
     * @template S
     * @template B
     * @param callable $runReader
     * @psalm-param callable(S): B $runReader
     * @return self
     * @psalm-return self<S, B>
     * @psalm-pure
     */
    public static function reader(callable $runReader): self
    {
        return new self($runReader);
    }

    /**
     * @param mixed $state
     * @psalm-param R $state
     * @return mixed
     * @psalm-return A
     * @psalm-pure
     */
    public function runReader($state)
    {
        return ($this->runReader)($state);
    }

    /**
     * @template B
     * @param callable $f
     * @psalm-param callable(A): B $f
     * @return self
     * @psalm-return self<R,B>
     * @psalm-pure
     */
    public function map(callable $f): self
    {
        $newRunReader =
            /**
             * @psalm-param R $r
             * @psalm-return B
             */
            fn($r) => $f($this->runReader($r));

        return self::reader($newRunReader);
    }
}
