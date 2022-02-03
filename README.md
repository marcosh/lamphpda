# lamPHPda

A collection of type-safe functional data structures

## Aim

The aim of this library is to provide a collection of functional data
structures in the most type safe way currently possible within the PHP
ecosystem, still providing a generic and consistent API.

## Main ideas

The two ideas which differentiate this from other functional libraries in PHP
are:

- a safe usage of sum types, following [http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html](http://marcosh.github.io/post/2017/10/27/maybe-in-php-2.html)
- the usage of higher kinded types to increase reusability and type safety
    (see [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-issue.html)
    and [http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html](http://marcosh.github.io/post/2020/04/15/higher-kinded-types-php-solution.html))

## Installation

There is currently no stable version available, we are currently working on it.

To install the latest development branch:

```shell
composer require marcosh/lamphpda:dev-master
```

## Tools

We use [Psalm](https://psalm.dev/) as a type checker. It basically works as a
compilation step, ensuring that all the types are aligned.

To benefit from this library, it is compulsory that your code runs through a
Psalm check.

## Decision record

The relevant decisions regarding the project are collected in the [`adr`](adr) folder, following the
[Architectural decision record](https://adr.github.io/) format

## Content

The library provides several immutable data structures useful to write applications in a functional style.

Currently, the implemented data structures are:

- [Maybe](src/Maybe.php), which allows modelling data which could be missing;
- [Either](src/Either.php), which models the idea of alternative;
- [Identity](src/Identity.php), which is just a simple wrapper
- [LinkedList](src/LinkedList.php), which models the possibility of having multiple values;
- [Pair](src/Pair.php), which models having two thing at the same time;
- [Reader](src/Reader.php), which models values which depend on a context;
- [State](src/State.php), which models values which can interact with a global state;
- [IO](src/IO.php), which models lazy values.

You can find more details about the implementation and the idea behind each data structure in the
[docs/data-structures folder](docs/data-structures).

## How to interact with the data structures

The library is built to be extremely abstract and generic to allow extreme composability and reusability.

There are various ways which you can use to interact with the provided data structures.

### Typeclasses

You can think of typeclasses as of behaviours which could be attached to a data structure. Since a data structure could
in principle have more than one way to implement a specific behaviour (e.g., there's more than one way to use two
integers to compute a new integer), we can not use directly interfaces to be implemented by our data structures.
Therefore, typeclass instances are implemented as separate independent objects implementing an interface which describes
the typeclass itself.

For example, the `Semigroup` typeclass, which describes the behaviour of [putting together two things of the same type
to obtain a thing of the same type](http://marcosh.github.io/post/2020/08/21/type-equality-in-object-oriented-programming.html),
could be implemented as

```php
/**
 * @template A
 */
interface Semigroup
{
    /**
     * @param A $a
     * @param A $b
     * @return A
     */
    public function append($a, $b);
}
```

Now we can implement a `Semigroup` instance for any type we want, even for native types. For example, we could implement
a semigroup for addition between integers

```php
/**
 * @implements Semigruop<int>
 */
final class IntAddition implements Semigroup
{
    /**
     * @param int $a
     * @param int $b
     * @return int
     */
    public function append($a, $b): int
    {
        return $a + $b;
    }
}
```

Then we could use it to sum two integers

```php
(new IntAddition())->append(1, 2); // returns 3
```

This specific instance is not that interesting, but the fact that you could write code which depends on a generic
`Semigroup` definitely is!

The typeclasses we are currently exposing are:

- [Functor](src/Typeclass/Functor.php), which allows lifting functions of one argument to a given context;
- [Apply](src/Typeclass/Apply.php), which allows lifting functions of any arity to a given context;
- [Applicative](src/Typeclass/Applicative.php), which allows lifting values to a context;
- [Alternative](src/Typeclass/Alternative.php), which models the ability of combining values wrapped in a context;
- [Monad](src/Typeclass/Monad.php), which allows sequencing functions which return a value in a context;
- [MonadThrow](src/Typeclass/MonadThrow.php), which allows managing exceptions in a pure way
- [Foldable](src/Typeclass/Foldable.php), which allows to shrink a data structure to a single value;
- [Traversable](src/Typeclass/Traversable.php), which allows transforming a data structure with a function returning values in an applicative context;
- [Semigroup](src/Typeclass/Semigroup.php), which allows combining two values of the same type;
- [Monoid](src/Typeclass/Monoid.php), which allows creating an identity element;
- [Bifunctor](src/Typeclass/Bifunctor.php), which models context which depends on two covariant type variables;
- [Profunctor](src/Typeclass/Profunctor.php), which models context which depends on a contravariant and a covariant type variable.

More details on each typeclass can be found in the [docs/typeclasses folder](docs/typeclasses).

### Typeclasses and data structures

As a [design principle](adr/2021-11-22-methods-come-from-typeclasses.md) for this library, we try to expose on our data
structures only methods which come from a typeclass. This means that the provided data structure have a standard common
API which makes use of typeclasses instances.

For example, `Either` has two `Apply` instances. To choose which one you want to use, `Either` exposes the `iapply`
method which takes as first argument an instance of an `Apply` typeclass for `Either`.

```php
/**
 * @template A
 * @template B
 */
final class Either
{
    /**
     * @template C
     * @param Apply<EitherBrand<A>> $apply
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     * @return Either<A, C>
     */
    public function iapply(Apply $apply, HK1 $f): self
}
```

We are able to specify that a typeclass instance refers to a specific data structure using the so-called
[`Brands`](src/Brand/Brand.php), which are nothing else that tags at the type level which enable us to simulate higher
kinded types.

### Default typeclass instances

More often than not a data structure admits only one instance of a typeclass, or there exists one which is considered
standard in the literature. In such cases it is quite inconvenient to sustain the burden of passing the typeclass
instance; to ease the pain, we expose also the method where the default typeclass instance is already provided.

Continuing with the example in the previous section, `Either` exposes also a method `apply` where the `EitherApply`
instance is hardcoded.

```php
/**
 * @template A
 * @template B
 */
final class Either
{
    /**
     * @template C
     * @param HK1<EitherBrand<A>, callable(B): C> $f
     * @return Either<A, C>
     */
    public function apply(HK1 $f): self
    {
        return $this->iapply(new EitherApply(), $f);
    }
}
```

## Contributing

If you wish to contribute to the project, please read the [CONTRIBUTING notes](CONTRIBUTING.md).
