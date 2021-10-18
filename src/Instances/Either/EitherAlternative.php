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
use Marcosh\LamPHPda\Instances\FirstSemigroup;
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
    public function pure($a): Either
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
        return Either::left($this->eMonoid->mempty());
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

        return (new MeetEitherSemigroup($this->eMonoid, new FirstSemigroup()))->append($aEither, $bEither);
    }
}
