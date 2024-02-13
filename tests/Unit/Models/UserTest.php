<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test if a user can be created
     *
     * @return void
     */
    public function test_can_create_user()
    {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
        ];

        $user = User::create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals($userData['name'], $user->name);
        $this->assertEquals($userData['email'], $user->email);
    }

    /**
     * Test if a user can be retrieved
     *
     * @return void
     */
    public function test_can_retrieve_user()
    {
        $user = User::factory()->create();

        $retrievedUser = User::find($user->id);

        $this->assertInstanceOf(User::class, $retrievedUser);
        $this->assertEquals($user->name, $retrievedUser->name);
        $this->assertEquals($user->email, $retrievedUser->email);
    }

    /**
     * Test if a user can be deleted.
     *
     * @return void
     */
    public function test_delete_user()
    {
        $user = User::factory()->create();
        $user->delete();
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
