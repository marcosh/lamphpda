<?php

declare(strict_types=1);

namespace Marcosh\LamPHPda;

/**
 * The `Unit` class is fairly useless in itself, given that by design it contains no data.
 *
 * Still, it could prove useful when you would use `void` but you actually need an inhabited type.
 * For example, this could happen when you're calling `eval` on a `Maybe` and the `$ifJust` branch
 * is processing its input with some side effects not tracked at the type level.
 *
 * $maybe->eval(
 *   ...,
 *   function ($a): void {
 *     ...
 *   }
 * );
 *
 * This won't work because the return type of the `$ifJust` case needs to be the same type as the `$ifNothing` case.
 * But `void` is inhabited and hence there's no value you can use there (except throwing an exception, which is probably
 * not what you want).
 *
 * With `Unit` you can solve this as follows
 *
 * $maybe->eval(
 *   new Unit(),
 *   function ($a): Unit {
 *     ...
 *     return new Unit();
 *   }
 * );
 */
final class Unit
{
}
