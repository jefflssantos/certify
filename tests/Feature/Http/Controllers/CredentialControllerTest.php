<?php

namespace Tests\Feature\Http\Controllers;

use App\Jobs\CreateCredentialJob;
use App\Models\Credential;
use App\Models\Module;
use App\Models\User;
use DateTime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Queue;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CredentialControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_module_credentials(): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->create(['user_id' => $user->id]);
        Credential::factory(17)->create(['module_id' => $module->id]);

        $response = $this->getJson(route('modules.credentials.store', $module));

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['uuid', 'issued_to', 'email', 'image', 'pdf', 'expires_at', 'created_at', 'updated_at']
                ]
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('meta')
                    ->has('links')
                    ->has('data', 15)
            );
    }

    /** @test  */
    public function it_should_forbid_an_unauthenticated_user_to_list_credentials(): void
    {
        $user = User::factory()->create();
        $module = Module::factory()->create(['user_id' => $user->id]);

        $response = $this->getJson(route('modules.credentials.index', $module));

        $response->assertUnauthorized()
            ->assertExactJson(['message' => 'Unauthenticated.']);

        $this->assertDatabaseCount('credentials', 0);
    }

    /** @test */
    public function it_should_create_a_credential_successfylly(): void
    {
        Queue::fake();
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->create(['user_id' => $user->id]);
        $credential = Credential::factory()->make();

        $response = $this->postJson(route('modules.credentials.store', $module), $credential->toArray());

        $response->assertStatus(JsonResponse::HTTP_ACCEPTED);

        $this->assertDatabaseHas('credentials', [
            'module_id' => $module->id,
            'issued_to' => $credential->issued_to,
            'email' => $credential->email
        ]);

        Queue::assertPushed(CreateCredentialJob::class);
    }

    /**
     * @test
     * @dataProvider invalidData
     */
    public function it_should_fail_when_invalid_data_is_provided_to_create_a_credential(array $data, array $errors): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->create(['user_id' => $user->id]);

        $response = $this->postJson(route('modules.credentials.store', $module), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($errors);

        $this->assertDatabaseCount('credentials', 0);
    }

    public function invalidData(): array
    {
        return [
            'No data provided' => [
                'data' => [],
                'errors' => ['issued_to', 'email']
            ],
            'Invalid fields format' => [
                'data' => [
                    'issued_to' => 'John Doe',
                    'email' => 'invalid-email.com',
                    'expires_at' => (new DateTime('+ 60 days'))->format('d/m/Y')
                ],
                'errors' => ['email', 'expires_at']
            ]
        ];
    }

    /** @test */
    public function it_should_delete_a_credential_successfylly(): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->create(['user_id' => $user->id]);
        $credential = Credential::factory()->create(['module_id' => $module->id]);

        $response = $this->deleteJson(route('modules.credentials.destroy', [$module, $credential]));

        $response->assertOk()
            ->assertExactJson(['message' => 'Credential successfully deleted.']);

        $this->assertModelMissing($credential);
    }

    /** @test */
    public function it_should_forbid_an_user_to_delete_a_credential_from_a_different_module(): void
    {
        $user = $this->createAndActingAsUser();

        $modules = Module::factory(2)->create(['user_id' => $user->id]);
        $credential = Credential::factory()->create(['module_id' => $modules[0]->id]);

        $response = $this->deleteJson(route('modules.credentials.destroy', [$modules[1], $credential]));

        $response->assertForbidden();

        $this->assertModelExists($credential);
    }

    /** @test */
    public function it_should_forbid_an_unauthorized_user_to_delete_a_module(): void
    {
        $this->createAndActingAsUser();

        $user = User::factory()->create();
        $module = Module::factory()->create(['user_id' => $user->id]);
        $credential = Credential::factory()->create(['module_id' => $module->id]);

        $response = $this->deleteJson(route('modules.credentials.destroy', [$module, $credential]));

        $response->assertForbidden();

        $this->assertModelExists($credential);
    }
}
