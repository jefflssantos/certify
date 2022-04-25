<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_login_successfully(): void
    {
        $user = User::factory()->create(['password' => Hash::make('password')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertOk()
            ->assertExactJson(['api_token' => $user->api_token]);
    }

    /** @test */
    public function it_should_fail_when_user_not_exists(): void
    {
        $user = User::factory()->make(['password' => Hash::make('password')]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password'
        ]);

        $response->assertUnauthorized()
            ->assertExactJson(['message' => 'The provided credentials do not match our records.']);

        $this->assertModelMissing($user);
    }

    /** @test */
    public function it_should_fail_when_login_with_wrong_password(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password'
        ]);

        $response->assertUnauthorized()
            ->assertExactJson(['message' => 'The provided credentials do not match our records.']);
    }


    /** @test */
    public function it_should_fail_when_required_fields_are_missing(): void
    {
        $response = $this->postJson('/api/login');

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The email field is required. (and 1 more error)',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.']
                ]
            ]);
    }

    /** @test */
    public function it_should_fail_when_email_is_invalid(): void
    {
        $response = $this->postJson('/api/login', [
            'email' => 'invalid-email.com',
            'password' => 'wrong-password'
        ]);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The email must be a valid email address.',
                'errors' => [
                    'email' => ['The email must be a valid email address.']
                ]
            ]);
    }
}
