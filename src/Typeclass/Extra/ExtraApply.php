<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Apply;

/**
 * @template F of Brand
 *
 * @psalm-immutable
 */
final class ExtraApply
{
    /**
     * @var Apply<F>
     */
    private Apply $apply;

    /**
     * @param Apply<F> $apply
     */
    public function __construct(Apply $apply)
    {
        $this->apply = $apply;
    }

    /**
     * @template A
     * @template B
     * @template C
     *
     * @param callable(A, B): C $f
     * @param HK1<F, A> $a
     * @param HK1<F, B> $b
     *
     * @return HK1<F, C>
     *
     * @psalm-suppress InvalidReturnType
     */
    public function lift2(callable $f, HK1 $a, HK1 $b): HK1
    {
        $curriedF =
            /**
             * @param A $ca
             *
             * @return callable(B): C
             */
            fn ($ca) =>
                /**
                 * @param B $cb
                 *
                 * @return C
                 */
            fn ($cb) => $f($ca, $cb);

        /** @psalm-suppress InvalidReturnStatement */
        return $this->apply->apply(
            $this->apply->map($curriedF, $a),
            $b
        );
    }
}
