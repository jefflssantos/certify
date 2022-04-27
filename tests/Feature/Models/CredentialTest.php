<?php

namespace Tests\Feature\Models;

use App\Models\Credential;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Tests\TestCase;

class CredentialTest extends TestCase
{
    /** @test */
    public function a_credential_should_belongs_to_module(): void
    {
        $this->assertInstanceOf(BelongsTo::class, (new Credential())->module());
    }
}
