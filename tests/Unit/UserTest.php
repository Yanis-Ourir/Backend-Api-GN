<?php
namespace Tests\Unit;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;


class UserTest extends TestCase
{
    public function testUserIsCreatedWithProperties(): void
    {
        $user = User::factory()->make([
            'pseudo' => 'test',
            'email' => 'testunitaire@test.com',
            'password' => 'password'
        ]);


        expect($user->pseudo)->toBe('test')
            ->and($user->email)->toBe('testunitaire@test.com');

        $this->assertTrue(Hash::check('password', $user->password));

    }

    public function testUserIsSendingAnErrorWithAMissingPseudo(): void
    {
        $userRepository = new UserRepository(new User());

        $user = $userRepository->create([
            'email' => 'testunitaire@gmail.com',
            'password' => 'password'
        ]);

        $errors = $user['errors']->getMessages();
        expect(array_key_exists('pseudo', $errors))->toBe(true);
    }

    public function testUserIsSendingAnErrorWithAMissingEmail(): void
    {
        $userRepository = new UserRepository(new User());
        $user = $userRepository->create([
            'pseudo' => 'test',
            'password' => 'password'
        ]);

        $errors = $user['errors']->getMessages();
        expect(array_key_exists('email', $errors))->toBe(true);
    }

    public function testUserIsSendingAnErrorWithAMissingPassword(): void
    {
        $userRepository = new UserRepository(new User());
        $user = $userRepository->create([
            'pseudo' => 'test',
            'email' => 'testunitaire@test.com'
        ]);

        $errors = $user['errors']->getMessages();
        expect(array_key_exists('password', $errors))->toBe(true);
    }

    public function testUserIsSendingAnErrorWithAnInvalidEmail(): void
    {
        $userRepository = new UserRepository(new User());
        $user = $userRepository->create([
            'pseudo' => 'test',
            'email' => 'testunitaire',
            'password' => 'password'
        ]);

        $errors = $user['errors']->getMessages();
        expect(array_key_exists('email', $errors))->toBe(true);
    }

    public function testUserIsCreatingInDB(): void
    {
        $userRepositoryMock = Mockery::mock(UserRepository::class);
        $userRepositoryMock->shouldReceive('create')->once()->andReturn([
            'pseudo' => 'test',
            'email' => 'testunitaire@gmail.com',
        ]);

        $user = $userRepositoryMock->create([
            'pseudo' => 'test',
            'email' => 'testunitaire@gmail.com',
            'password' => 'password'
        ]);

        expect($user['pseudo'])->toBe('test')
            ->and($user['email'])->toBe('testunitaire@gmail.com');
    }
}
