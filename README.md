# lamphpda

A collection of type-safe functional data structures

## Aim

The aim of this library is to provide a collection of functional data
structures in the most type safe way currently possible within the PHP
ecosystem.

## Main ideas

The two ideas which differentiate this from other functional libraries in PHP
are:

- a safe usage of sum types following [http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html](http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html)
- the usage of higher kinded types to increase reusability and type safety
    (see [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html)
    and [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html))

## Tools

We use [Psalm](https://psalm.dev/) as a type checker. It basically works as a
compilation step, ensuring that all the types are aligned.

To benefit from this library, it is compulsory that your code runs through a
Psalm check.

## Content

The library provides some immutable data structures useful to write applications in a functional style.
It models those structures using algebraic data types encoded in the following way:

- products are just objects with several properties;
- sums are objects with several independent constructors, one for each component of the sum. The approach is described
more in details in [http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html](http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html);

### `Either` as a working example

Let's consider the `Either` data structure. It represents the possibility of choice, the fact that one particular data
could be one thing or another. It is encoded as a sum type with two constructors, called `left` and `right`

```php
/**
 * @template A
 * @template B
 */
final class Either
{
    /** @var bool */
    private $isRight;

    /** @var A|null */
    private $leftValue;

    /** @var B|null */
    private $rightValue;

    /**
     * @param bool $isRight
     * @param A|null $leftValue
     * @param B|null $rightValue
     */
    private function __construct(bool $isRight, $leftValue = null, $rightValue = null)
    {
        $this->isRight = $isRight;
        $this->leftValue = $leftValue;
        $this->rightValue = $rightValue;
    }

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

The `$isRight` property is internal state used to keep track whether the specific value is a `left` or a `right` and is
never exposed externally.
The two public named constructors `left` and `right` are the only ways to build an `Either` object.
The `Either` type is parametrised by two type variables, `A` and `B`, used respectively to track the type of left and
right values.
Written more synthetically, the type of `left` and `right` are

```
left :: C -> Either<C, D>
right :: D -> Either<C, D>
```

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
