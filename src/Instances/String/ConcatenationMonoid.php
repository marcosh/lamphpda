<?php

/**
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Marcosh\LamPHPda\Instances\String;

use Marcosh\LamPHPda\Typeclass\Monoid;

/**
 * @implements Monoid<string>
 *
 * @psalm-immutable
 */
final class ConcatenationMonoid implements Monoid
{

    /**
     * @param string $a
     * @param string $b
     */
    public function append($a, $b): string
    {
        return $a . $b;
    }
    public function mempty(): string
    {
        return '';
    }
}
