<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @template R
 * @template A
 * @implements Functor<ReaderBrand, A>
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
     * @return Reader
     * @psalm-return Reader<S, B>
     * @psalm-pure
     */
    public static function reader(callable $runReader): Reader
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
     * @return Reader
     * @psalm-return Reader<R, B>
     * @psalm-pure
     */
    public function map(callable $f): Reader
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
