<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate();

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data["password"] = bcrypt($request->password);
        $user = User::create($data);

        return new UserResource($user);
    }

    public function show(string $userId)
    {
        $user = User::findOrFail($userId);

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, string $userId)
    {
        $data = $request->validated();

        if (isset($request->password)) {
            $data['password'] = bcrypt($request->password);
        }

        $user = User::findOrFail($userId);
        $user->update($data);

        return new UserResource($user);
    }

    public function destroy(string $userId)
    {
        $user = User::findOrFail($userId);
        $user->delete();

        return new UserResource($user);
    }
}
