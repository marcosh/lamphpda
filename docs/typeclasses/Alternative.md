# Alternative

The `Alternative` typeclass provides monoid-like operations on type constructors instead of values.

## Parent

The `Alternative` typeclass extends the `Applicative` typeclass and the `Plus` typeclass.

## API

The `Alternative` typeclass do not expose any new method with respect to `Applicative` and `Plus`.

## Laws

The `Alternative` typeclass require more laws to hold, in addition to the ones required by the `Applicative` and `Plus` typeclasses.

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
