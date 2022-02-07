# Apply

A data structure admits an `Apply` instance if allows lifting a function of any arity.

Equivalently, an `Apply` instance allows applying a function to a value where both are wrapped in the data structure.

## Parent

The `Apply` typeclass extends the `Functor` typeclass.

## API

The `Apply` typeclass adds a single method to the `Functor` one, which allows to apply a wrapped function to a wrapped
value.

```php
interface Apply extends Functor
{
    /**
     * @param F<callable(A): B> $f
     * @param F<A> $a
     * @return F<B>
     */
    public function apply($f, $a);
}
```

Its simplified signature is

```
apply :: (F<callable(A): B>, F<A>) -> F<B>
```

### Extra

The `ExtraApply` class exposes a series of methods to lift functions of higher arity. For example `lift2` allows to lift
functions of arity 2.

```php
final class ExtraApply
{
    /**
     * @param callable(A, B): C $f
     * @param F<A> $a
     * @param F<B> $b
     * @return F<C>
     */
    public function lift2(callable $f, $a, $b)
```

It simplified signature is

```
lift2 :: (callable(A): B, F<A>, F<B>) -> F<C>
```

## Laws

Instances of the `Apply` typeclass need to satisfy one law in addition to the `Functor` laws, which states that
application is associative

```php
$apply->apply($apply->apply($apply->map(fn ($f, $g) => fn ($x) => $f($g($x)), $a), $b), $c) == $apply->apply($a, $apply->apply($b, $c))
```

## Implemented instances

- `EitherApply`
- `ValidationApply`
- `IdentityApply`
- `IOApply`
- `LinkedListApply`
- `MaybeApply`
- `PairApply`
- `ReaderApply`
- `StateApply`
