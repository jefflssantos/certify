<?php

namespace Tests\Feature\Models;

use App\Models\Module;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_module_should_belongs_to_user(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new Module())->user());
    }
}
