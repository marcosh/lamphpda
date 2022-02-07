# Monoid

A `Monoid` is a `Semigroup` with an identity element.

## Parent

The `Monoid` typeclass extends the `Semigroup` typeclass.

## API

The `Monoid` typeclass adds e single method to the `Semigroup` typeclass, providing the ability to retrieve the identity
element for the associative operation.

```php
interface Monoid extends Semigroup
{
    /**
     * @return A
     */
    public function mempty();
}
```

Its simplified type is

```
mempty :: () -> A
```

## Laws

The `Monoid` laws ensure that the element returned by `mempty` is in fact the identity of the associative operation.

### Right identity

```php
$monoid->append($a, $monoid->mempty()) == $a
```

### Left identity

```php
$monoid->append($monoid->mempty(), $a) == $a
```

## Implemented instances

- `LinkedListMonoid`
