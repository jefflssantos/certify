<?php

namespace Tests\Feature\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Tests\TestCase;
use Illuminate\Support\Str;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function is_should_register_successfylly(): void
    {
        $data = User::factory()
            ->make(['password' => 'any-password'])
            ->only('name', 'email', 'password');

        $response = $this->postJson('/api/register', $data);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'access_token']);

        $this->assertDatabaseCount('users', 1);
    }

    /** @test */
    public function it_should_fail_when_user_already_exists(): void
    {
        $user = User::factory()->create();
        $userTwo = User::factory()->make(['email' => $user->email]);

        $response = $this->postJson('/api/register', $userTwo->only('name', 'email', 'password'));
        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson([
                'message' => 'The email has already been taken.',
                'errors' => ['email' => ['The email has already been taken.']]
            ]);

        $this->assertModelExists($user);
        $this->assertModelMissing($userTwo);
    }

    /**
     * @test
     * @dataProvider invalidRegisterData
     */
    public function it_should_fail_when_invalid_data_is_provided(array $data, array $expectedResponse): void
    {
        $response = $this->postJson('/api/register', $data);

        $response->assertStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->assertExactJson($expectedResponse);

        $this->assertDatabaseCount('users', 0);

    }

    public function invalidRegisterData(): array
    {
        return [
            'All fields are invalid' => [
                'data' => ['name' => '1', 'email' => 'invalid-email.com', 'password' => '1245'],
                'expectedResponse' => [
                    'message' => 'The name must be between 2 and 100 characters. (and 2 more errors)',
                    "errors" => [
                        'email' => ['The email must be a valid email address.'],
                        'name' => ['The name must be between 2 and 100 characters.'],
                        'password' => ['The password must be at least 6 characters.']
                    ]
                ]
            ],
            'All fields are empty' => [
                'data' => ['name' => '', 'email' => '', 'password' => ''],
                'expectedResponse' => [
                    'message' => 'The name field is required. (and 2 more errors)',
                    "errors" => [
                        'email' => ['The email field is required.'],
                        'name' => ['The name field is required.'],
                        'password' => ['The password field is required.']
                    ]
                ]
            ],
            'Name has more than 100 caracteres' => [
                'data' => ['name' => Str::random(101), 'email' => 'valid@certify.io', 'password' => '123456'],
                'expectedResponse' => [
                    'message' => 'The name must be between 2 and 100 characters.',
                    "errors" => [
                        'name' => ['The name must be between 2 and 100 characters.']
                    ]
                ]
            ]
        ];
    }
}
