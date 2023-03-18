# Functor

A data structure admits a `Functor` instance if it depends on a type variable and it is possible to map over that type
variable.

A `Functor` instance provides the ability to lift a function `A -> B` into the context provided by the data structure.

## API

The `Functor` typeclass has a single method, which lifts functions of type `A -> B` into the context of the data
structure.

```php
interface Functor
{
    /**
     * @param callable(A): B $f
     * @param F<A> $a
     * @return F<B>
     *
     * @psalm-pure
     */
    public function map(callable $f, $a);
}
```

It simplified type is

```
map :: ((A -> B), F<A>) -> F<B>
```

## Laws

A `Functor` instance needs to abide by two laws:

### Identity

The identity function should be mapped to the identity function

```php
$functor->map(fn(mixed $x): mixed => $x, $y) == $y
```

### Composition

Mapping the composition of two functions or composing the mapping of those functions should be the same thing

```php
$functor->map(fn(mixed $x): mixed => $f($g($x)), $y) == $functor->map(fn(mixed $x): mixed => $f($x), $functor->map(fn(mixed $x): mixed => $g($x), $y))
```

## Implemented instances

- `EitherFunctor`
- `IdentityFunctor`
- `IOApplyFunctor`
- `LinkedListFunctor`
- `MaybeFunctor`
- `PairFunctor`
- `ReaderFunctor`
- `StateFunctor`
