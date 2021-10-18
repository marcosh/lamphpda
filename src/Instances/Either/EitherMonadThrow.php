<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\MonadThrow;

/**
 * @template E
 *
 * @implements MonadThrow<EitherBrand, E>
 *
 * @psalm-immutable
 */
final class EitherMonadThrow implements MonadThrow
{
    /**
     * @template A
     * @template B
     * @template C
     * @param pure-callable(A): B $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function map(callable $f, HK1 $a): HK1
    {
        return (new EitherFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param HK1<EitherBrand<C>, callable(A): B> $f
     * @param HK1<EitherBrand<C>, A> $a
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): Either
    {
        return (new EitherApply())->apply($f, $a);
    }

    /**
     * @template A
     * @template B
     * @param A $a
     * @return Either<B, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure($a): Either
    {
        return (new EitherApplicative())->pure($a);
    }

    /**
     * @template A
     * @template B
     * @template C
     * @param HK1<EitherBrand<C>, A> $a
     * @param callable(A): HK1<EitherBrand<C>, B> $f
     * @return Either<C, B>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function bind(HK1 $a, callable $f): Either
    {
        return (new EitherMonad())->bind($a, $f);
    }

    /**
     * @template A
     * @param E $e
     * @return Either<E, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function throwError($e): Either
    {
        return Either::left($e);
    }
}
