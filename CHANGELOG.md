# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic versioning](http://semver.org/).

## [1.4.1] - 2023-09-28

- fix `Traversable` instance for `ListL`

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
