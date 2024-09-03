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
    /**
     * Summary of userService
     * @var
     */
    protected $userService;
    /**
     * Summary of __construct
     * @param \App\Services\UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->middleware('admin');

        $this->userService = $userService;
    }
    /**
     * Summary of index
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {

        $perPage = $request->input('per_page', 15);
        $users = $this->userService->listAllUsers($perPage);
        return ApiResponseService::paginated(UserResource::collection($users),'users retrieves success');

    }

    /**
     * Summary of store
     * @param \App\Http\Requests\RegisterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        $data = $request->validated();
       $user =$this->userService->createUser($data);
       return ApiResponseService::success(new UserResource($user),'user created success');
    }

    /**
     * Summary of show
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        $this->userService->getUser($user);
        return ApiResponseService::success($user,'get user success');
    }

    /**
     * Summary of update
     * @param \App\Http\Requests\UpadateUserRequest $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpadateUserRequest $request, User $user)
    {
        $data = $request->validated();
        $user = $this->userService->updateUser($data,$user);
        return ApiResponseService::success($user,'user update success');
    }

    /**
     * Summary of destroy
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
     $this->userService->deleteUser($user);
     return ApiResponseService::success(null,'user delete success',201);
    }
}
