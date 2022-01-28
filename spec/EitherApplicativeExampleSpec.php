<?php

declare(strict_types=1);

namespace EitherApplicativeExampleSpec {

    use Marcosh\LamPHPda\Brand\EitherBrand;
    use Marcosh\LamPHPda\Either;
    use Marcosh\LamPHPda\Instances\Either\EitherApply;
    use Marcosh\LamPHPda\Instances\Either\ValidationApply;
    use Marcosh\LamPHPda\Typeclass\Apply;
    use Marcosh\LamPHPda\Typeclass\Extra\ExtraApply;
    use Marcosh\LamPHPda\Typeclass\Semigroup;

// our model is just a user with a name and an age

// phpcs:ignore PSR1.Classes.ClassDeclaration.MissingNamespace
    final class Name
    {
        public string $name;

        private function __construct(string $name)
        {
            $this->name = $name;
        }

        /**
         * name should contain only characters
         *
         * @return Either<string[], Name>
         */
        public static function validate(string $name): Either
        {
            if (!preg_match('/^[a-zA-Z]*$/', $name)) {
                return Either::left(['name contains non alphabetic characters']);
            }

            return Either::right($name);
        }
    }

// phpcs:ignore PSR1.Classes.ClassDeclaration.MultipleClasses, PSR1.Classes.ClassDeclaration.MissingNamespace
    final class Age
    {
        public int $age;

        private function __construct(int $age)
        {
            $this->age = $age;
        }

        /**
         * age should not be negative
         *
         * @param int $age
         * @return Either<string[], Age>
         */
        public static function validate(int $age): Either
        {
            if ($age < 0) {
                return Either::left(['age is negative']);
            }

            return Either::right($age);
        }
    }

// phpcs:ignore PSR1.Classes.ClassDeclaration.MultipleClasses, PSR1.Classes.ClassDeclaration.MissingNamespace
    final class User
    {
        public Name $name;

        public Age $age;

        public function __construct(Name $name, Age $age)
        {
            $this->name = $name;
            $this->age = $age;
        }
    }

// the User class can be constructed using a Name and an Age,
// but we can't directly build a Name and an Age, since their public constructors actually return an Either.
// We need to lift the constructor of User to the context of Either, so that we can apply it to Either<string[], Name>
// and Either<string[], Age>.
// This is a work for an Apply instance!

    /**
     * @param Apply<EitherBrand> $applicative
     */
    function validateUser(Apply $applicative, string $name, int $age)
    {
        return (new ExtraApply($applicative))->lift2(
            fn(Name $name, Age $age) => new User($name, $age),
            Name::validate($name),
            Age::validate($age)
        );
    }

    /**
     * @template
     *
     * @implements Semigroup<array<A>>
     */
// phpcs:ignore PSR1.Classes.ClassDeclaration.MultipleClasses, PSR1.Classes.ClassDeclaration.MissingNamespace
    final class ArraySemigroup implements Semigroup
    {
        /**
         * @param array<A> $a
         * @param array<A> $b
         * @return array<A>
         */
        public function append($a, $b): array
        {
            return array_merge($a, $b);
        }
    }

    describe('Either applicative instances example', function () {
        describe('Default instance', function () {
            it('returns the first validation error', function () {
                // if we use the default apply instance for Either, we will get back only the first error message
                // the behaviour is that of "fail first"

                expect(validateUser(new EitherApply(), '123', -13))
                    ->toEqual(Either::left(['name contains non alphabetic characters']));
            });
        });

        describe('Validation instance', function () {
            it('collects validation errors', function () {
                // if we use the validation instance for Either, we will collect all the error messages
                // to do that we need a semigroup instance for the error message type

                expect(validateUser(new ValidationApply(new ArraySemigroup()), '123', -13))
                    ->toEqual(Either::left(['name contains non alphabetic characters', 'age is negative']));
            });
        });
    });

}
