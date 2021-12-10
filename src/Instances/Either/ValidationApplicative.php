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
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template E
 *
 * @implements Applicative<EitherBrand>
 *
 * @psalm-immutable
 */
final class ValidationApplicative implements Applicative
{
    /**
     * @var Semigroup<E>
     */
    private Semigroup $semigroup;

    /**
     * @param Semigroup<E> $semigroup
     */
    public function __construct(Semigroup $semigroup)
    {
        $this->semigroup = $semigroup;
    }

    /**
     * @template A
     * @template B
     *
     * @param HK1<EitherBrand<E>, callable(A): B> $f
     * @param HK1<EitherBrand<E>, A> $a
     *
     * @return Either<E, B>
     *
     * @psalm-mutation-free
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function apply(HK1 $f, HK1 $a): HK1
    {
        return (new ValidationApply($this->semigroup))->apply($f, $a);
    }

    /**
     * @template A
     * @template B
     *
     * @param pure-callable(A): B $f
     * @param HK1<EitherBrand<E>, A> $a
     *
     * @return Either<E, B>
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
     *
     * @param A $a
     *
     * @return Either<B, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure($a): Either
    {
        return Either::right($a);
    }
}
