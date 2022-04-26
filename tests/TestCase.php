<?php

namespace Tests;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Sanctum\Sanctum;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function createUser(): Authenticatable
    {
        return User::create(User::factory()->make()->toArray() + ['password' => 123456]);
    }

    protected function createAndActingAsUser(): Authenticatable
    {
        Sanctum::actingAs($user = $this->createUser());

        return $user;
    }
}
