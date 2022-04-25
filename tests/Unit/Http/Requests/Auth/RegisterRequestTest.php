<?php

namespace Tests\Unit\Http\Requests\Auth;

use App\Http\Requests\Auth\RegisterRequest;
use PHPUnit\Framework\TestCase;

class RegisterRequestTest extends TestCase
{
    /** @test */
    public function it_should_authorize_the_request(): void
    {
        $request = new RegisterRequest();

        $this->assertTrue($request->authorize());
    }

    /** @test */
    public function it_should_contain_all_the_expected_validation_rules(): void
    {
        $request = new RegisterRequest();

        $this->assertEquals([
            'name' => ['required', 'string', 'between:2,100'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'min:6']
        ], $request->rules());
    }
}
