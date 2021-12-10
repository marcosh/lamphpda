<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda;

use Marcosh\LamPHPda\Brand\PairBrand2;
use Marcosh\LamPHPda\HK\HK2Covariant;

/**
 * @template A
 * @template B
 *
 * @implements HK2Covariant<PairBrand2, A, B>
 *
 * @psalm-immutable
 */
final class Pair implements HK2Covariant
{
    /**
     * @var A
     */
    private $left;

    /**
     * @var B
     */
    private $right;

    /**
     * @param A $left
     * @param B $right
     */
    private function __construct($left, $right)
    {
        $this->left = $left;
        $this->right = $right;
    }

    /**
     * @template C
     * @template D
     *
     * @param C $left
     * @param D $right
     *
     * @return Pair<C, D>
     *
     * @psalm-pure
     */
    public static function pair($left, $right): self
    {
        return new self($left, $right);
    }

    /**
     * @template C
     * @template D
     *
     * @param HK2Covariant<PairBrand2, C, D> $hk
     *
     * @return Pair<C, D>
     *
     * @psalm-pure
     */
    public static function fromBrand2(HK2Covariant $hk): self
    {
        /** @var Pair<C, D> */
        return $hk;
    }

    /**
     * @template C
     *
     * @param callable(A, B): C $f
     *
     * @return C
     */
    public function eval(callable $f)
    {
        return $f($this->left, $this->right);
    }
}
