<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_list_user_modules(): void
    {
        $user = $this->createAndActingAsUser();

        Module::factory(20)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/modules');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'created_at', 'updated_at']
                ]
            ])
            ->assertJson(fn (AssertableJson $json) =>
                $json->has('meta')
                    ->has('links')
                    ->has('data', 15)
            );
    }

    /** @test  */
    public function it_should_forbid_an_unauthenticated_user_to_list_modules(): void
    {
        $response = $this->getJson(route('modules.index'));

        $response->assertUnauthorized()
            ->assertExactJson(['message' => 'Unauthenticated.']);

        $this->assertDatabaseCount('modules', 0);
    }

    /** @test */
    public function it_should_create_a_module_successfylly(): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->make();

        $response = $this->postJson('/api/modules', $module->toArray());

        $response->assertCreated()
            ->assertJsonStructure(['data' => ['id', 'name', 'description', 'created_at', 'updated_at']]);

        $this->assertDatabaseHas('modules', [
            'user_id' => $user->id,
            'name' => $module->name,
            'description' => $module->description
        ]);
    }

    /**
     * @test
     * @dataProvider invalidData
     */
    public function it_should_fail_when_invalid_data_is_provided_to_create_a_module(array $data, array $errors): void
    {
        $this->createAndActingAsUser();

        $response = $this->postJson(route('modules.store'), $data);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors($errors);

        $this->assertDatabaseCount('modules', 0);

    }

    public function invalidData(): array
    {
        return [
            'No data provided' => [
                'data' => [],
                'errors' => ['name']
            ],
            'Name is missing' => [
                'data' => ['description' => 'some text'],
                'errors' => ['name']
            ],
        ];
    }

    /** @test */
    public function it_should_fail_when_creating_an_existing_module(): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->make()->toArray();
        $user->modules()->create($module);

        $response = $this->postJson('/api/modules', $module);

        $response->assertUnprocessable()
            ->assertExactJson([
                'message' => 'The name has already been taken.',
                'errors' => ['name' => ['The name has already been taken.']]
            ]);

        $this->assertDatabaseCount('modules', 1);
    }

    /** @test */
    public function it_should_update_a_module_successfylly(): void
    {
        $user = $this->createAndActingAsUser();

        $currentModule = Module::factory()->create(['user_id' => $user->id]);
        $module = Module::factory()->make();

        $response = $this->putJson("/api/modules/{$currentModule->id}", $module->toArray());

        $response->assertOk()
            ->assertJsonStructure(['data' => [
                'id', 'name', 'description', 'created_at', 'updated_at']
            ]);

        $this->assertDatabaseHas('modules', [
            'id' => $currentModule->id,
            'user_id' => $user->id,
            'name' => $module->name,
            'description' => $module->description
        ]);
    }

    /** @test */
    public function it_should_forbid_an_unauthorized_user_to_update_a_module(): void
    {
        $this->createAndActingAsUser();

        $user = User::factory()->create();
        $module = Module::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/modules/{$module->id}", $module->toArray());

        $response->assertForbidden();
    }

    /** @test */
    public function it_should_delete_a_module_successfylly(): void
    {
        $user = $this->createAndActingAsUser();

        $module = Module::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/modules/{$module->id}");

        $response->assertOk()
            ->assertExactJson(['message' => 'Module successfully deleted.']);

        $this->assertModelMissing($module);
    }

    /** @test */
    public function it_should_forbid_an_unauthorized_user_to_delete_a_module(): void
    {
        $this->createAndActingAsUser();

        $user = User::factory()->create();
        $module = Module::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/modules/{$module->id}");

        $response->assertForbidden();

        $this->assertModelExists($module);
    }
}
