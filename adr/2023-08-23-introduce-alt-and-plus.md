# Introduce `Alt` and `Plus`

## Context

The `Alternative` typeclass represents the behavior of a monoid for types of kind `* -> *`. A typeclass for representing semigroups of kind `* -> *` is missing.

Currently, the `Validation` method `or` requires a monoid, while in theory a `Semigroup` would be enough. This is due to the fact that `or` is implemented on top of `Alternative`.

## Decision

Following the Purescript typeclass hierarchy, we decide to introduce the `Alt` and the `Plus` typeclass.

## Consequences

We have now more possibilities when dealing with semigroups/monoids of kinds `* -> *`.

We can also implement `Validation::or` in terms of `Alt`, requiring a semigroup instead of a monoid.
