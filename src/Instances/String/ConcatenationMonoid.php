<?php

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
    public function mempty(): string
    {
        return '';
    }

    /**
     * @param string $a
     * @param string $b
     */
    public function append(mixed $a, mixed $b): string
    {
        return $a . $b;
    }
}
