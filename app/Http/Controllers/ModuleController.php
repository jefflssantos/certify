<?php

namespace App\Http\Controllers;

use App\Http\Requests\Module\StoreRequest;
use App\Http\Requests\StoreModuleRequest;
use App\Http\Requests\UpdateModuleRequest;
use App\Http\Resources\ModuleResource;
use App\Models\Module;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ModuleController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function store(StoreRequest $request): ModuleResource
    {
        $data = $request->validated();
        // $data['user_id'] = Auth::userId();
        $data['user_id'] = User::first()->id;

        $module = Module::create($data);

        return new ModuleResource($module);
    }
}
