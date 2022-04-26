<?php

namespace App\Http\Controllers;

use App\Http\Requests\Module\StoreRequest;
use App\Http\Requests\Module\UpdateRequest;
use App\Http\Requests\Modules\DeleteRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResource
    {
        $modules = Auth::user()->modules()->latest()->paginate();

        return ModuleResource::collection($modules);
    }

    public function store(StoreRequest $request): ModuleResource
    {
        $data = $request->validated();

        $module = Auth::user()->modules()->create($data);

        return new ModuleResource($module);
    }

    public function update(UpdateRequest $request, Module $module): ModuleResource
    {
        $module->update($request->validated());

        return new ModuleResource($module);
    }

    public function destroy(Module $module): JsonResponse | Exception
    {
        abort_if($module->user_id != Auth::user()->id, JsonResponse::HTTP_FORBIDDEN);

        $module->delete();

        return response()->json(['message' => 'Module successfully deleted.']);
    }
}
