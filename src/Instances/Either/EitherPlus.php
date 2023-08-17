<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Plus;
use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @template E
 *
 * @implements Plus<EitherBrand<E>>
 *
 * @psalm-immutable
 */
final class EitherPlus implements Plus
{
    /** @var Monoid<E> */
    private Monoid $eMonoid;

    /**
     * @param Monoid<E> $eMonoid
     */
    public function __construct(Monoid $eMonoid)
    {
        $this->eMonoid = $eMonoid;
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

    /**
     * @template A
     * @return Either<E, A>
     *
     * @psalm-suppress LessSpecificImplementedReturnType
     */
    public function empty(): Either
    {
        return Either::left($this->eMonoid->mempty());
    }
}
