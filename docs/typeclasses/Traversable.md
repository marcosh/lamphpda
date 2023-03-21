# Traversable

The `Traversable` typeclass represents data structures which can be traversed, accumulating results and effects in some
`Applicative` functor.

## Parents

The `Traversable` typeclass extends the `Functor` and the `Foldable` typeclasses.

## API

The `Traversable` typeclass provides a single method, which allows traversing applying an applicative effect for every
element.

```php
interface Traversable extends Functor, Foldable
    /**
     * @param Applicative<F> $applicative
     * @param callable(A): F<B> $f
     * @param T<A> $a
     * @return F<T<B>>
     */
    public function traverse(Applicative $applicative, callable $f, $a);
```

## Extra

The `ExtraTraversable` class provides an additional method which could be used with `Traversable` instances, allowing to
traverse a data structure evaluating applicative operations and collecting the result.

```php
final class ExtraTraversable
{
    /**
     * @param Applicative<F> $applicative
     * @param T<F<A>> $tf
     * @return F<T<A>>
     */
    public function sequence($applicative, $tf)
}
```

## Laws

Additionally to the ones already defined in `Functor` and `Foldable`, every instance of `Traversable` should satisfy the
following laws.

### Naturality

```php
$t($traversable->traverse($applicative, $f, $x)) == $traversable->traverse($applicative, fn($y) => $t($f($y)), $x)
```

### Identity

```php
$traversable->traverse(new IdentityApplicative(), fn($x) => new Identity($x), $y) == new Identity($y)
```

### Composition

// TODO

## Implemented instances

- `EitherTraversable`
- `IdentityTraversable`
- `LinkedListTraversable`
- `MaybeTraversable`
