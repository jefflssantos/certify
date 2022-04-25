<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['api_token'] = $this->generateApiToken();

        $user = User::create($data);

        return response()->json([
            'message' => 'User successfully registered.',
            'user' => $user->only('name', 'email', 'api_token', 'created_at')
        ], JsonResponse::HTTP_CREATED);
    }

    private function generateApiToken(): string
    {
        return Str::random(60);
    }
}
