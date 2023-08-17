# Alt

The `Alt` typeclass provides semigroup-like operations on type constructors instead of values.

## Parent

The `Alt` typeclass extends the `Functor` typeclass

## API

The `Alt` typeclass provides a single associative binary operation.

```php
interface Alt extends Functor
{
    /**
     * @param F<A> $a
     * @param F<A> $b
     * @return F<A>
     */
    public function alt($a, $b);
}
```

Its simplified type is

```
alt :: F<A> -> F<A> -> F<A>
```

## Laws

### Associativity

```php
$alternative->alt($alternative->alt($x, $y), $z) == $alternative->alt($x, $alternative->alt($y, $z))
```

### Distributivity with respect to map

```php
$alternative->map($f, $alternative->alt($x, $y)) == $alternative->alt($alternative->map($f, $x), $alternative->map($f, $y))
```

## Implemented instances

- `EitherAlt`
