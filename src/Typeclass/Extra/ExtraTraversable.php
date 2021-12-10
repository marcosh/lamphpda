<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass\Extra;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;
use Marcosh\LamPHPda\Typeclass\Applicative;
use Marcosh\LamPHPda\Typeclass\Traversable;

/**
 * @template T of Brand
 *
 * @psalm-immutable
 */
final class ExtraTraversable
{
    /**
     * @var Traversable<T>
     */
    private $traversable;

    /**
     * @param Traversable<T> $traversable
     */
    public function __construct(Traversable $traversable)
    {
        $this->traversable = $traversable;
    }

    /**
     * @template F of Brand
     * @template A
     *
     * @param Applicative<F> $applicative
     * @param HK1<T, HK1<F, A>> $hk
     *
     * @return HK1<F, HK1<T, A>>
     */
    public function sequence($applicative, $hk)
    {
        return $this->traversable->traverse(
            $applicative,
            /**
             * @param HK1<F, A> $fa
             *
             * @return HK1<F, A>
             */
            fn ($fa) => $fa,
            $hk
        );
    }
}
