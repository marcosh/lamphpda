<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Functor;
use Marcosh\LamPHPda\Typeclass\Monad;

/**
 * @template R
 * @template A
 * @implements Functor<ReaderBrand, A>
 * @implements Apply<ReaderBrand, A>
 * @implements Applicative<ReaderBrand, A>
 * @implements Monad<ReaderBrand, A>
 * @psalm-immutable
 */
final class Reader implements Applicative, Apply, Functor, Monad
{
    /**
     * @var callable
     * @psalm-var callable(R): A
     */
    private $runReader;

    /**
     * @psalm-param callable(R): A $runReader
     * @psalm-pure
     */
    private function __construct(callable $runReader)
    {
        $this->runReader = $runReader;
    }

    /**
     * @template B
     * @psalm-param Apply<ReaderBrand, callable(A): B> $f
     * @psalm-return Reader<R, B>
     * @psalm-pure
     */
    public function apply(Apply $f): Reader
    {
        $f = self::fromBrand($f);

        $newRunReader =
            /**
             * @psalm-param R $r
             * @psalm-return B
             */
            fn ($r) => ($f->runReader($r))($this->runReader($r));

        return self::reader($newRunReader);
    }

    /**
     * @template B
     * @psalm-param callable(A): Monad<ReaderBrand, B> $f
     * @psalm-return Reader<R, B>
     * @psalm-pure
     */
    public function bind(callable $f): Reader
    {
        $newRunReader =
            /**
             * @psalm-param R $r
             * @psalm-return B
             */
            fn ($r) => self::fromBrand($f(($this->runReader)($r)))->runReader($r);

        return self::reader($newRunReader);
    }

    /**
     * @template B
     * @psalm-param callable(A): B $f
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
            fn ($r) => $f($this->runReader($r));

        return self::reader($newRunReader);
    }

    /**
     * @template S
     * @template B
     *
     * @param mixed $a
     * @psalm-param B $a
     * @psalm-return Reader<S, B>
     * @psalm-pure
     */
    public static function pure($a): Reader
    {
        return self::reader(
            /**
             * @psalm-param S $r
             */
            static fn ($r) => $a
        );
    }

    /**
     * @template S
     * @template B
     * @psalm-param callable(S): B $runReader
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
     *
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
     * @psalm-param HK<ReaderBrand, B> $hk
     * @psalm-return Reader<R, B>
     * @psalm-pure
     */
    private static function fromBrand(HK $hk): Reader
    {
        /** @var Reader $hk */
        return $hk;
    }
}
