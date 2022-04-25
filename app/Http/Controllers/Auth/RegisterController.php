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
        $user = User::create($request->validated());

        $token = $user->createToken(config('sanctum.token_name'));

        return response()->json([
            'message' => 'User successfully registered.',
            'access_token' => $token->plainTextToken
        ], JsonResponse::HTTP_CREATED);
    }
}
