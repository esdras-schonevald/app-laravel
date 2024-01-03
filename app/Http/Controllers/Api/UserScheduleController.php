<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserScheduleRequest;
use App\Http\Requests\UpdateUserScheduleRequest;
use App\Http\Resources\ActivityResource;
use App\Http\Resources\UserScheduleResource;
use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;

class UserScheduleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(string $userId, Request $request)
    {
        $initialDate    =   $request->input('initialDate');
        $finalDate      =   $request->input('finalDate');

        $user       =   User::findOrFail($userId);
        $query      =   Activity::where("user_id", $userId);

        if ($initialDate && $finalDate) {
            $query->whereBetween('start_date', [$initialDate, $finalDate]);
        }

        $activities = $query->paginate(10);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'password' => '******',
            'schedules' => ActivityResource::collection($activities),
            'pagination' => [
                'total' => $activities->total(),
                'current_page' => $activities->currentPage(),
                'per_page' => $activities->perPage(),
                'last_page' => $activities->lastPage(),
                'from' => $activities->firstItem(),
                'to' => $activities->lastItem(),
            ],
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(string $userId, StoreUserScheduleRequest $request)
    {
        $data               =   $request->validated();
        $data['user_id']    =   $userId;
        $user               =   User::findOrFail($userId);
        $schedule           =   Activity::create($data);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'password' => '******',
            'schedules' => [
                new ActivityResource($schedule)
            ]
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $userId, string $scheduleId)
    {
        $user = User::findOrFail($userId);
        $schedule = Activity::findOrfail($scheduleId);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'password' => '******',
            'schedules' => [
                new ActivityResource($schedule)
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $userId, string $scheduleId, UpdateUserScheduleRequest $request)
    {
        $data               =   $request->validated();
        $user               =   User::findOrFail($userId);
        $schedule           =   Activity::findOrFail($scheduleId);

        $schedule->update($data);

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'password' => '******',
            'schedules' => [
                new ActivityResource($schedule)
            ]
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $userId, string $scheduleId)
    {
        $user               =   User::findOrFail($userId);
        $schedule           =   Activity::findOrFail($scheduleId);
        $schedule->delete();

        return response()->json([
            'name' => $user->name,
            'email' => $user->email,
            'password' => '******',
            'schedules' => [
                new ActivityResource($schedule)
            ]
        ]);
    }
}
