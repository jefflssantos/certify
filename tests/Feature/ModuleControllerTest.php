<?php

namespace Tests\Feature;

use App\Models\Module;
use App\Models\User;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_should_create_a_module_successfylly(): void
    {
        $user = $this->createUser();

        $response = $this->actingAs($user)
            ->postJson('/api/modules', Module::factory()->make()->toArray());
        $response->dd();
        $response->assertStatus(200);
    }
}
