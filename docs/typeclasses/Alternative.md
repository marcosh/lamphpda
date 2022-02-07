# Alternative

The `Alternative` typeclass provides monoid-like operations on type constructors instead of values.

## Parent

The `Alternative` typeclass extends the `Applicative` typeclass

## API

The `Alternative` typeclass provides two methods, an associative binary operation and an identity element.

```php
interface Alternative extends Applicative
{
    /**
     * @return F<A>
     */
    public function empty();

    /**
     * @param F<A> $a
     * @param F<A> $b
     * @return F<A>
     */
    public function alt($a, $b);
}
```

Their simplified type is

```
empty :: () -> F<A>
alt :: F<A> -> F<A> -> F<A>
```

## Laws

The `Alternative` typeclass require several laws to hold, in addition to the ones required by the `Functor`, `Apply` and
`Applicative` typeclasses.

### Associativity

```php
$alternative->alt($alternative->alt($x, $y), $z) == $alternative->alt($x, $alternative->alt($y, $z))
```

### Distributivity with respect to map

```php
$alternative->map($f, $alternative->alt($x, $y)) == $alternative->alt($alternative->map($f, $x), $alternative->map($f, $y))
```

### Left identity

```php
$alternative->alt($alternative->empty(), $x) == $x
```

### Right identity

```php
$alternative->alt($x, $alternative->empty()) == $x
```

### Annihilation with respect to map

```php
$alternative->map($f, $alternative->empty()) == $alternative->empty()
```

### Distributivity with respect to apply

```php
$alternative->apply($alternative->alt($f, $g), $x) == $alternative->alt($alternative->apply($f, $x), $alternative->apply($g, $x))
```

### Annihilation with respect to apply

```php
$alternative->apply($alternative->empty(), $f) == $alternative->empty()
```

## Implemented instances

- `EitherAlternative`
