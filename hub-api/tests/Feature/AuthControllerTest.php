<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase; 

    public function test_register_user_successfully(): void
    {
        // Simulate a request payload for registration
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        // Assert that the registration was successful
        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User registered successfully.',
            ]);

        // Assert that the user was created in the database
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    public function test_user_can_login_successfully(): void
    {
        // Create a user in the database
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Attempt to login
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'password123',
        ]);

        // Assert successful login
        $response->assertStatus(200)->assertJsonPath('user.name', $user->name); 
    }

    public function test_user_login_fails_with_wrong_credentials(): void
    {
        // Create a user in the database
        $user = User::factory()->create([
            'email' => 'johndoe@example.com',
            'password' => Hash::make('password123'),
        ]);

        // Attempt to login with wrong password
        $response = $this->postJson('/api/login', [
            'email' => 'johndoe@example.com',
            'password' => 'wrongpassword',
        ]);

        // Assert failed login
        $response->assertStatus(422) // Validation failed
            ->assertJsonPath("message", "These credentials do not match our records.");
    }

    public function test_user_can_logout_successfully(): void
    {
        // Create and authenticate a user
        $user = User::factory()->create();
        $this->actingAs($user);

        // Attempt to logout
        $response = $this->postJson('/api/logout');

        // Assert successful logout
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'You are logged out',
                 ]);
    }
}
