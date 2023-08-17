<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda\Typeclass;

use Marcosh\LamPHPda\Brand\Brand;
use Marcosh\LamPHPda\HK\HK1;

/**
 * @see https://github.com/marcosh/lamphpda/tree/master/docs/typeclasses/Alternative.md
 *
 * @template F of Brand
 *
 * @extends Applicative<F>
 * @extends Plus<F>
 *
 * @psalm-immutable
 */
interface Alternative extends Applicative, Plus
{
}
