# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic versioning](http://semver.org/).

## [3.1.1]

- add type hints to `OppositeSemigroup` and `OppositeMonoid`

## [3.1.0]

- add `voidRight` in `ExtraFunctor`
- add `toNullable` in `Maybe`

## [3.0.1] - 2023-09-28

- fix `Traversable` instance for `ListL`

## [3.0.0] - 2023-09-27

- added `flake.nix` to provide a development environment
- introduce `OppositeSemigroup` and `OppositMonoid`
- do not flip arguments on `ListL/ConcatenationMonoid`
- introduce `Alt` and `Plus` typeclasses
- define `ConstantSemigroup`
- correct `foldr` implementation for `ListL` and `Traversable`

## [2.0.0] - 2023-03-21

- use `mixed` type annotations
- restrict `PHP` version to `>= 8.1`
## [1.4.0] - 2023-09-27

- introduce `OppositeSemigroup` and `OppositMonoid`
- do not flip arguments on `ListL/ConcatenationMonoid`
- correct `foldr` implementation for `ListL` and `Traversable`

## [1.3.0] - 2023-08-23

- introduce `Alt` and `Plus` typeclasses
- define `ConstantSemigroup`

## [1.2.0] - 2022-07-04

- add `Maybe::withLazyDefault`

## [1.1.1] - 2022-05-30

- add missing type annotations
- remove composer.lock

## [1.1.0] - 2022-02-09

- constructor of `ExtraMonad` should be public
