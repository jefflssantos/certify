<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        if (! Auth::attempt($data)) {
            return response()->json([
                'message' => 'The provided credentials do not match our records.'
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $user = User::whereEmail($data['email'])->first();

        return response()->json([
            "api_token" => $user->api_token
        ]);
    }
}
