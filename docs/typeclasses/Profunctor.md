# Profunctor

The `Profunctor` models a generalization of functions. Its instances have two type variables, one which changes in a
covariant fashion, the other contravariantly.

## API

The `Profunctor` typeclass provides a single method which allows mapping on both type variables at the same time,
covariantly on one and contravariantly on the other.

```php
interface Profunctor
{
    /**
     * @param callable(A): B $f
     * @param callable(C): D $g
     * @param F<B, C> $a
     * @return F<A, D>
     */
    public function diMap(callable $f, callable $g, $a);
}
```

Its simplified type signature is

```
(A -> B, C -> D, F<B, C>) -> F<A, D>
```

## Extra

The `ExtraProfunctor` class uses the `Profunctor` instance to map separately on the covariant and on the contravariant
type variables.

```php
final class ExtraProfunctor
{
    /**
     * @param callable(A): B $f
     * @param F<C, A> $fca
     * @return F<C, B>
     */
    public function rmap(callable $f, $fca)

    /**
     * @param callable(A): B $f
     * @param F<B, C> $fbc
     * @return F<A, C>
     */
    public function lmap(callable $f, $fbc)
}
```

Their simplified type is

```
rmap :: (A -> B, F<C, A>) -> F<C, B>
lmap :: (A -> B, F<B, C>) -> F<A, C>
```

## Laws

The laws of the `Profunctor` typeclass are the same of the `Bifunctor` typeclass, with the slight twist given by the
contravariant type variable.

### Identity

```php
$profunctor->diMap(fn(mixed $x): mixed => $x, fn(mixed $x): mixed => $x, $a) == $a
```

### Composition

```php
$profunctor->diMap(fn(mixed $x): mixed => $f($g($x)), fn(mixed $x): mixed => $h($k($x)), $a) == $profunctor->diMap(fn(mixed $x): mixed => $g($x), fn(mixed $x): mixed => $h($x), $profunctor->diMap(fn(mixed $x): mixed => $f($x), fn(mixed $x): mixed => $k($x)), $a)
```

