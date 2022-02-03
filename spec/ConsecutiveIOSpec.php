<?php

declare(strict_types=1);

use Marcosh\LamPHPda\IO;

// our model is just a `User` with an id and a name

/**
 * @psalm-immutable
 */
final class IOUser
{
    public int $id;

    public string $name;

    public function __construct(int $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
    }
}

// `getUser` plays the role of function which interacts with the database to retieve a `User` from its `id`

/**
 * @param int $id
 * @return IO<IOUser>
 */
function getUser(int $id): IO
{
    return IO::action(fn () => new IOUser($id, 'marco'));
}

// 'retrieveRemoteData' plays the role of a network call which, given the name of the user, retrieves an integer value

/**
 * @param IOUser $user
 * @return IO<int>
 */
function retrieveRemoteData(IOUser $user): IO
{
    return IO::action(fn () => strlen($user->name));
}

// we can now combine the two function calls into a single one which retrieves the integer value from the id of the user

describe('IO can sequence interactions with the external world', function () {
    it('combines getUser and retrieveRemoteData', function () {
        $getUser = getUser(0); // no call to the database is performed here
        $getUserAndRetrieveRemoteData = $getUser->bind('retrieveRemoteData'); // no side effect is performed here

        // only when we call eval the side effects are actually performed
        expect($getUserAndRetrieveRemoteData->eval())->toBe(5);
    });
});
