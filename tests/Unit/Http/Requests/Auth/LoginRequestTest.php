<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\LoginRequest;
use PHPUnit\Framework\TestCase;

class LoginRequestTest extends TestCase
{
    /** @test */
    public function it_should_authorize_the_request(): void
    {
        $request = new LoginRequest();

        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function it_should_contain_all_the_expected_validation_rules(): void
    {
        $request = new LoginRequest();

        $this->assertEquals([
            'email' => ['required', 'email'],
            'password' => ['required', 'string']
        ], $request->rules());
    }
}
