# Either

The `Either` data structure represents the possibility of choice, the fact that one particular datum
could be one thing or another.

## Constructors

There are two constructors, called `left` and `right`

```php
/**
 * @template A
 * @template B
 */
final class Either
{
    /**
     * @template C
     * @template D
     * @param C $value
     * @return Either<C, D>
     */
    public static function left($value): Either
    {
        return new self(false, $value);
    }

    /**
     * @template C
     * @template D
     * @param D $value
     * @return Either<C, D>
     */
    public static function right($value): Either
    {
        return new self(true, null, $value);
    }
```

Written more synthetically, the type of `left` and `right` are

```
left :: C -> Either<C, D>
right :: D -> Either<C, D>
```

## Eliminators

Now that we know how to build an `Either`, we need a way to consume it. The API exposes only one method to consume an
`Either`, all other methods will need to use it internally. This method is called `eval` and allows us to consume the
inner value of `Either` providing two callbacks, one for the `left` case, one for the `right` one.

```php
    /**
     * @template C
     * @param callable(A): C $ifLeft
     * @param callable(B): C $ifRight
     * @return C
     */
    public function eval(
        callable $ifLeft,
        callable $ifRight
    ) {
        if ($this->isRight) {
            return $ifRight($this->rightValue);
        }

        return $ifLeft($this->leftValue);
    }
```

Its simplified type is

```
eval :: (Either<A, B>, A -> C, B -> C) -> C
```

## Interpretation as an effect

The dataytpe `Either<A, B>` is usually seen as a context where values of type `B` live with the possibility of producing
error messages of type `A`.

## Typeclass instances

### Semigroup

We define two instances of `Semigroup` for `Either`. The difference between the two lies on their behaviour when they
need to combine a `left` and a `right`.

### Apply and Applicative

We define two instances of `Apply` and `Applicative` for `Either`. The difference lies in their behaviour with respect
to two `left`. The default instance, which then extends also to a monad, has a fail first behaviour, i.e. as soon as a
`left` is encountered, it is returned. The other instance, called `Validation`, on the other hand uses a semigroup on
`A` to accumulate errors.
