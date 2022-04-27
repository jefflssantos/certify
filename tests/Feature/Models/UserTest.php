<?php

namespace Tests\Feature\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    public function a_user_should_have_many_modules(): void
    {
        $this->assertInstanceOf(HasMany::class, (new User())->modules());
    }

}
