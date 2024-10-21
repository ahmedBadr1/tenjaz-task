<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->userService->all();
        return successResponse(UserResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return successResponse(data: UserResource::make($user), statusCode: 201);
    }

    /**
     * Create a new user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function show(int $id)
    {
        $user = $this->userService->find($id);
        if ($user) {
            return successResponse(UserResource::make($user));
        }
        return errorResponse(message: 'User not found', statusCode: 404);
    }


    /**
     * Update an existing user.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUserRequest $request, int $id)
    {
        $user = $this->userService->update(data: $request->validated(), id: $id);
        if ($user) {
            return successResponse(UserResource::make($user));
        }
        return errorResponse(message: 'User not found', statusCode: 404);
    }

    /**
     * Delete a user.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id)
    {
        $deleted = $this->userService->delete($id);
        if ($deleted) {
            return successResponse(message: 'User deleted successfully');
        }
        return errorResponse(message: 'User not found', statusCode: 404);
    }
}
