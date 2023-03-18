# Applicative

A data structure admits an `Applicative` instance if it allows lifting values into the data structure.

## Parent

The `Applicative` typeclass extends the `Apply` typeclass.

## API

The `Applicative` typeclass adds a single method which allows wrapping values in the data structure.

```php
interface Applicative extends Apply
{
    /**
     * @param A $a
     * @return F<A>
     */
    public function pure(mixed $a);
}
```

Its simplified signature is

```
pure :: A -> F<A>
```

## Laws

The `Applicative` typeclass needs to satisfy four laws in addition to the `Apply` and `Functor` ones.

### Identity

```php
$applicative->apply($applicative->pure(fn(mixed $x): mixed => $x), $y) == $y
```

### Composition

```php
$applicative->apply($applicative->apply($applicative->apply($applicative->pure(fn ($f, $g) => fn ($x) => $f($g($x))), $a), $b), $c) == $applicative->apply($a, $applicative->apply($b, $c))
```

### Homomorphism

```php
$applicative->apply($applicative->pure($f), $applicative->pure($x)) == $applicative->pure($f($x))
```

### Interchange

```php
$applicative->apply($f, $applicative->pure($x)) == $applicative->apply(fn (callable $g) => $g($x), $f)
```

## Implemented instances

- `EitherApplicative`
- `ValidationApplicative`
- `IdentityApplicative`
- `IOApplicative`
- `LinkedListApplicative`
- `MaybeApplicative`
- `PairApplicative`
- `ReaderApplicative`
- `StateApplicative`
