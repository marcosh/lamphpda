# Typeclass instances are their own objects

## Context

To implement basic functional programming data structures and patterns, we want to implement concepts as `Functor`,
`Applicative`, `Monad`, ... which are typeclasses in Haskell. These are basically sets of behaviours attached to
particular data types. 

In an object-oriented world, the first idea would be to say, for example, that `Maybe<A> implements Functor`, adding
a `map` method to the `Maybe` class. This actually falls short because of limitations of the tools we are using (PHP and
Psalm); for example, it's not possible to require constraints in a method signature, which is needed for example for
defining [`traverse`](https://hackage.haskell.org/package/base-4.15.0.0/docs/Prelude.html#v:traverse).

Moreover, using the `Maybe<A> implements Functor` idiom limits us to have one unique instance of a typeclass for a
specific data types. This could be acceptable, since it's also what Haskell does.

## Decision

We decide to treat instances as their own objects, which basically describe how a typeclass works for a specific
datatype.
For example, we will have a `MaybeFunctor` class which will describe a specific instance (actually, the default one) of
`Functor` for the `Maybe` data type.
Then in the `Maybe` class itself we will have an `imap` method which will receive a `Functor` implementation for `Maybe`
to provide mapping over the inner value.
When a datatype has a default instance for a typeclass (i.e. it's the typeclass instance which Haskell provides), we
provide also a method exposing directly the functionality on the class itself. For example, `Maybe` has also a `Map`
method specializing `map` to `MaybeFunctor`.

This provides also the possibility to use several instances of the same typeclass for a given datatype.

## Consequences

We will implement typeclasses not an interfaces which will be implemented by the datatypes themselves, but we treat them
as separate objects.

Practically for a typeclass `Functor`, we will have an implementation `MaybeFunctor implements Functor` which will
implement the `Functor` interface. Then the relevant methods on `Maybe` will receive an instance of `MaybeFunctor` to
provide mapping functionalities for `Maybe`. Since `Maybe` has a default `Functor` instance, we will directly provide on
`Maybe` a `map` method using it.
