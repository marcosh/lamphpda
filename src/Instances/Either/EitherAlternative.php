<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Alternative;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template E
 *
 * @implements Alternative<EitherBrand<E>>
 *
 * @psalm-immutable
 */
final class EitherAlternative implements Alternative
{
    /**
     * @param Monoid<E> $eMonoid
     */
    public function __construct(private readonly Monoid $eMonoid)
    {
    }

    /**
     * @template A
     * @template B
     * @param callable(A): B $f
     * @param HK1<EitherBrand<E>, A> $a
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
     * @param HK1<EitherBrand<E>, callable(A): B> $f
     * @param HK1<EitherBrand<E>, A> $a
     * @return Either<E, B>
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
     * @param A $a
     * @return Either<E, A>
     *
     * @psalm-pure
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function pure(mixed $a): Either
    {
        return (new EitherApplicative())->pure($a);
    }

    /**
     * @template A
     * @return Either<E, A>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function empty(): Either
    {
        return (new EitherPlus($this->eMonoid))->empty();
    }

    /**
     * @template A
     * @param HK1<EitherBrand<E>, A> $a
     * @param HK1<EitherBrand<E>, A> $b
     * @return Either<E, A>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function alt(HK1 $a, HK1 $b): Either
    {
        return (new EitherAlt($this->eMonoid))->alt($a, $b);
    }
}
