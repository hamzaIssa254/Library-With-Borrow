<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpadateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\ApiResponseService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->middleware('admin');

        $this->userService = $userService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 15);
        $users = $this->userService->listAllUsers($perPage);
        return ApiResponseService::paginated(UserResource::collection($users),'users retrieves success');

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
       $user =$this->userService->createUser($data);
       return ApiResponseService::success(new UserResource($user),'user created success');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $this->userService->getUser($user);
        return ApiResponseService::success($user,'get user success');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpadateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $this->userService->updateUser($data,$user);
        return ApiResponseService::success($user,'user update success');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
     $this->userService->deleteUser($user);
     return ApiResponseService::success(null,'user delete success',201);
    }
}
