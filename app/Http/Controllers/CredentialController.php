<?php

namespace App\Http\Controllers;

use App\Http\Requests\Credentials\StoreRequest;
use App\Http\Resources\CredentialResource;
use App\Jobs\CreateCredentialJob;
use App\Models\Credential;
use App\Models\Module;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CredentialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Module $module): JsonResource
    {
        $credentials = $module->credentials()->latest()->paginate();

        return CredentialResource::collection($credentials);
    }

    public function store(Module $module, StoreRequest $request): JsonResponse
    {
        $credential = $module->credentials()->create($request->validated());
        CreateCredentialJob::dispatch($credential);

        return response()->json(['message' => 'Credential creation is queued.'], JsonResponse::HTTP_ACCEPTED);
    }

    public function destroy(Module $module, Credential $credential): JsonResponse
    {
        abort_if(
            $credential->module_id != $module->id || $module->user_id != Auth::user()->id,
            JsonResponse::HTTP_FORBIDDEN
        );

        $credential->delete();

        return response()->json(['message' => 'Credential successfully deleted.']);
    }
}
