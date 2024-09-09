<?php

namespace App\Modules\Account\Http\Controllers;

use App\Common\Enums\UserRole;
use App\Common\UserStatus;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Modules\Account\Http\Requests\ChangeActivationStatusRequest;
use App\Modules\Account\Http\Requests\SearchUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$statuses = DB::select('select distinct status from users');

        $columns = ['id', 'firstname', 'lastname', 'email', 'department', 'status', 'avatar', 'created_at'];
        return User::select($columns)->orderBy('department')->orderBy('lastname')->where('role', '!=', 'admin')->paginate();
        //'statuses' => $statuse
    }

    public function getStudents()
    {
        return User::where([
            'role' => UserRole::Student->value,
            'status' => UserStatus::Activated->name()
        ])
            ->get(['id', 'lastname', 'firstname', 'department']);
    }

    public function changeActivationStatus(ChangeActivationStatusRequest $request): JsonResponse
    {
        try {
            $params = $request->validated();
            $user = User::findOrFail(intval($params['user_id']));
            $user->status = $params['status'];

            return response()->json(['message' => $user->update()]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Not found'], 404);
        }
    }


    /**
     * @throws \Throwable
     */
    public function getTaskStatusesForAllStudents(Request $request)
    {
        $task_id = intval($request->input('task_id', 0));
        $lecture_id = intval($request->input('lecture_id', 0));
        $department = $request->input('department', 'all');
        $task_type = $request->input('task_type', -1);
        return response()->json(User::getTaskStatusesForAllStudents($task_id, $lecture_id, $department, $task_type));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $user;
    }


    public function search(SearchUserRequest $request)
    {
        $searchTerm = $request->validated('username', '');

        return User::where('lastname', 'LIKE', "%$searchTerm%")
                ->orWhere('firstname', 'LIKE', "%$searchTerm%")
                ->paginate();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return ['result' => $user->delete(), 'user' => $user];
    }
}
