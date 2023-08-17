<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Instances\FirstSemigroup;
use Marcosh\LamPHPda\Typeclass\Alt;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template E
 *
 * @implements Alt<EitherBrand<E>>
 *
 * @psalm-immutable
 */
final class EitherAlt implements Alt
{
    /** @var Semigroup<E> */
    private Semigroup $eSemigroup;

    /**
     * @param Semigroup<E> $eSemigroup
     */
    public function __construct(Semigroup $eSemigroup)
    {
        $this->eSemigroup = $eSemigroup;
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
        $aEither = Either::fromBrand($a);
        $bEither = Either::fromBrand($b);

        return (new MeetEitherSemigroup($this->eSemigroup, new FirstSemigroup()))->append($aEither, $bEither);
    }
}
