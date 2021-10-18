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
use Marcosh\LamPHPda\Typeclass\Functor;

/**
 * @implements Functor<ReaderBrand>
 *
 * @psalm-immutable
 */
final class ReaderFunctor implements Functor
{
    /**
     * @template A
     * @template B
     * @template E
     * @param callable(A): B $f
     * @param HK1<ReaderBrand, A> $a
     * @return Reader<E, B>
     *
     * @psalm-pure
     */
    public function map(callable $f, HK1 $a): Reader
    {
        $readerA = Reader::fromBrand($a);

        return Reader::reader(
            static fn ($env) => $f($readerA->runReader($env))
        );
    }
}
