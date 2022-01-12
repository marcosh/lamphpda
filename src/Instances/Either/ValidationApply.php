<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Brand\EitherBrand;
use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Apply;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * @template E
 *
 * @implements Apply<EitherBrand<E>>
 *
 * @psalm-immutable
 */
final class ValidationApply implements Apply
{
    /** @var Semigroup<E> */
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
     * @param pure-callable(A): B $f
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
    public function apply(HK1 $f, HK1 $a): HK1
    {
        $eitherF = Either::fromBrand($f);
        $eitherA = Either::fromBrand($a);

        /**
         * @psalm-suppress InvalidArgument
         */
        return $eitherF->eval(
            /**
             * @param E $ef
             * @return Either<E, B>
             */
            fn ($ef): Either => $eitherA->eval(
                /**
                 * @param E $ea
                 * @return Either<E, B>
                 */
                fn ($ea): Either => Either::left($this->semigroup->append($ef, $ea)),
                /**
                 * @param A $_a
                 * @return Either<E, B>
                 */
                static fn ($_a): Either => Either::left($ef)
            ),
            /**
             * @param callable(A): B $f
             * @return Either<E, B>
             */
            static fn ($f): Either => $eitherA->eval(
                /**
                 * @param E $e
                 * @return Either<E, B>
                 */
                static fn ($e): Either => Either::left($e),
                /**
                 * @param A $a
                 * @return Either<E, B>
                 */
                static fn ($a): Either => Either::right($f($a))
            )
        );
    }
}
