<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Either;

use Marcosh\LamPHPda\Either;
use Marcosh\LamPHPda\Typeclass\Semigroup;

/**
 * joins the errors with an E semigroup
 * if one fails, then it all fails
 * if both validations succeed, we join the results with a B semigroup
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
    /** @var Semigroup<E> */
    private Semigroup $eSemigroup;

    /** @var Semigroup<B> */
    private Semigroup $bSemigroup;

    /**
     * @param Semigroup<E> $eSemigroup
     * @param Semigroup<B> $bSemigroup
     */
    public function __construct(Semigroup $eSemigroup, Semigroup $bSemigroup)
    {
        $this->eSemigroup = $eSemigroup;
        $this->bSemigroup = $bSemigroup;
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
            fn($ea) => $b->eval(
                /**
                 * @param E $eb
                 * @return Either<E, B>
                 */
                fn($eb) => Either::left($this->eSemigroup->append($ea, $eb)),
                /**
                 * @param B $_
                 * @return Either<E, B>
                 */
                fn($_) => Either::left($ea)
            ),
            /**
             * @param B $va
             * @return Either<E, B>
             */
            fn($va) => $b->eval(
                /**
                 * @param E $eb
                 * @return Either<E, B>
                 */
                fn ($eb) => Either::left($eb),
                /**
                 * @param B $vb
                 * @return Either<E, B>
                 */
                fn($vb) => Either::right($this->bSemigroup->append($va, $vb))
            )
        );
    }
}
