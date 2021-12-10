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
 * Joins the errors with an E semigroup,
 * if one succeeds, then it all succeeds,
 * if both validations succeed, we join the results with a B semigroup.
 *
 * @template E
 * @template B
 *
 * @implements Semigroup<Either<E, B>>
 *
 * @psalm-immutable
 */
final class MeetEitherSemigroup implements Semigroup
{

    /**
     * @var Semigroup<B>
     */
    private Semigroup $bSemigroup;
    /**
     * @var Semigroup<E>
     */
    private Semigroup $eSemigroup;

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
     *
     * @return Either<E, B>
     *
     * @psalm-pure
     */
    public function append($a, $b): Either
    {
        return $a->eval(
        /**
         * @param E $ea
         *
         * @return Either<E, B>
         */
            fn ($ea) => $b->eval(
            /**
             * @param E $eb
             *
             * @return Either<E, B>
             */
                fn ($eb) => Either::left($this->eSemigroup->append($ea, $eb)),
                /**
                 * @param B $vb
                 *
                 * @return Either<E, B>
                 */
                fn ($vb) => Either::right($vb)
            ),
            /**
             * @param B $va
             *
             * @return Either<E, B>
             */
            fn ($va) => $b->eval(
            /**
             * @param E $_
             *
             * @return Either<E, B>
             */
                fn ($_) => Either::right($va),
                /**
                 * @param B $vb
                 *
                 * @return Either<E, B>
                 */
                fn ($vb) => Either::right($this->bSemigroup->append($va, $vb))
            )
        );
    }
}
