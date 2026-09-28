<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCompatibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_be_created_without_exposing_credentials(): void
    {
        $user = User::create([
            'name' => 'Test Student',
            'email' => 'student@example.test',
            'password' => 'test-password',
        ]);

        $user->forceFill(['remember_token' => 'test-token'])->save();
        $user->refresh();

        $this->assertDatabaseHas('users', ['email' => 'student@example.test']);
        $this->assertTrue(Hash::check('test-password', $user->password));
        $this->assertArrayNotHasKey('password', $user->toArray());
        $this->assertArrayNotHasKey('remember_token', $user->toArray());
    }
}
