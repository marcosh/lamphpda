# Bifunctor

A data structure admits a `Bifunctor` instance if it has two type variables and can be mapped functorially over both.

## API

The `Bifunctor` typeclass provides a single method which allows mapping over two type variables at the same time.

```php
interface Bifunctor
{
    /**
     * @param callable(A): C $f
     * @param callable(B): D $g
     * @param F<A, B> $a
     * @return F<C, D>
     */
    public function biMap(callable $f, callable $g, $a);
}
```

Its simplified signature is

```
(A -> C, B -> B, F<A, B>) -> F<C, D>
```

## Extra

The `ExtraBifunctor` class provides two methods which allows mapping exclusively on one of the two type variables.

```php
final class ExtraBifunctor
{
    /**
     * @param callable(A): C $f
     * @param F<A, B> $fab
     * @return F<C, B>
     */
    public function first(callable $f, $fab)

    /**
     * @param callable(B): C $g
     * @param F<A, B> $fab
     * @return F<A, C>
     */
    public function second(callable $g, $fab)
}
```

Their simplified type is

```
first :: (A -> C, F<A, B>) -> F<C, B>
second :: (B -> C, F<A, B>) -> F<A, C>
```

## Laws

The laws of the `Bifunctor` typeclass are the same appearing in the `Functor` typeclass, only applied to both type
variables

### Identity

```php
$bifunctor->biMap(fn($x) => $x, fn($x) => $x, $a) == $a
```

### Composition

```php
$bifunctor->biMap(fn($x) => $f($g($x)), fn($x) => $h($k($x)), $a) == $bifunctor->biMap(fn($x) => $f($x), fn($x) => $h($x), $bifunctor->biMap(fn($x) => $g($x), fn($x) => $k($x)), $a)
```
