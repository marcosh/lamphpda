# Monad

The `Monad` typeclass allows treating explicitly the sequencing of actions inside a given context.

## Parent

The `Monad` typeclass extends the `Applicative` typeclass.

## API

The `Monad` typeclass adds a single method to the `Applicative` typeclass, providing the ability of applying a function,
which produces a result in a context, to a value already in that context.

```php
interface Monad extends Applicative
{
    /**
     * @param F<A> $a
     * @param callable(A): F<B> $f
     * @return F<B>
     */
    public function bind($a, callable $f);
}
```

Its simplified signature is

```
bind :: (F<A>, A -> F<B>) -> F<B>
```

## Extra

The `ExtraMonad` class provides on additional method which could be used with `Monad` instances, which allow removing
additional layers of the wrapping datatype

```php
final class ExtraMonad
{
    /**
     * @param F<F<A>> $ffa
     * @return F<A>
     */
    public function join($ffa)
}
```

Its simplified signature is

```
join :: F<F<A>> -> F<A>
```

## Laws

A `Monad` instance should satisfy three more laws in addition to the ones specified by `Functor`, `Apply` and
`Applicative`

### Left identity

```php
$monad->bind($monad->pure($a), $f) == $f($a)
```

### Right identity

```php
$monad->bind($a, fn(mixed $x): mixed => $monad->pure($x)) == $a
```

### Associativity

```php
$monad->bind($a, fn(mixed $x): mixed => $monad->bind($f($x), $g)) == $monad->bind($monad->bind($a, $g), $g)
```

## Implemented instances

- `EitherMonad`
- `IdentityMonad`
- `IOMonad`
- `LinkedListMonad`
- `MaybeMonad`
- `PairMonad`
- `ReaderMonad`
- `StateMonad`
