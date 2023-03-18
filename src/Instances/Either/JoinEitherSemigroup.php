<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * joins the errors with an E semigroup
 * if one fails, then it all fails
 * if both validations succeed, we join the results with a B semigroup.
 *
 * @template E
 * @template B
 *
 * @implements Semigroup<Either<E, B>>
 *
 * @psalm-immutable
 */
final class JoinEitherSemigroup implements Semigroup
{
    /**
     * @param Semigroup<E> $eSemigroup
     * @param Semigroup<B> $bSemigroup
     */
    public function __construct(private readonly Semigroup $eSemigroup, private readonly Semigroup $bSemigroup)
    {
    }

    /**
     * @param Either<E, B> $a
     * @param Either<E, B> $b
     * @return Either<E, B>
     *
     * @psalm-pure
     */
    public function append($a, $b): Either
    {
        return $a->eval(
            /**
             * @param E $ea
             * @return Either<E, B>
             */
            fn (mixed $ea): Either => $b->eval(
                /**
                 * @param E $eb
                 * @return Either<E, B>
                 */
                fn (mixed $eb): Either => Either::left($this->eSemigroup->append($ea, $eb)),
                /**
                 * @param B $_
                 * @return Either<E, B>
                 */
                static fn (mixed $_): Either => Either::left($ea)
            ),
            /**
             * @param B $va
             * @return Either<E, B>
             */
            fn (mixed $va): Either => $b->eval(
                /**
                 * @param E $eb
                 * @return Either<E, B>
                 */
                static fn (mixed $eb): Either => Either::left($eb),
                /**
                 * @param B $vb
                 * @return Either<E, B>
                 */
                fn (mixed $vb): Either => Either::right($this->bSemigroup->append($va, $vb))
            )
        );
    }
}
