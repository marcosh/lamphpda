<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\Reader;

use Marcosh\LamPHPda\Brand\ReaderBrand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Reader;
use Marcosh\LamPHPda\Typeclass\Applicative;

/**
 * @implements Applicative<ReaderBrand>
 *
 * @psalm-immutable
 */
final class ReaderApplicative implements Applicative
{
    /**
     * @template A
     * @template B
     * @template E
     * @param callable(A): B $f
     * @param HK1<ReaderBrand<E>, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): Reader
    {
        return (new ReaderFunctor())->map($f, $a);
    }

    /**
     * @template A
     * @template B
     * @template E
     * @param HK1<ReaderBrand<E>, callable(A): B> $f
     * @param HK1<ReaderBrand<E>, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function apply(HK1 $f, HK1 $a): Reader
    {
        return (new ReaderApply())->apply($f, $a);
    }

    /**
     * @template A
     * @template E
     * @param A $a
     * @return Reader<E, A>
     *
     * @psalm-pure
     */
    public function pure($a): Reader
    {
        return Reader::reader(static fn($_) => $a);
    }
}
