<?php

namespace Tests\Feature\Models;

use App\Models\Module;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class ModuleTest extends TestCase
{
    /** @test */
    public function a_module_should_belongs_to_user(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new Module())->user());
    }
}
