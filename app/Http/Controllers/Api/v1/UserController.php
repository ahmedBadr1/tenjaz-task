<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="Tangez Documentation",
 *      description="API documentation for Tangez Task",
 *      @OA\Contact(
 *          email="badr4eldien@gmail.com"
 *      )
 * )
 * @OA\SecurityScheme(
 *       securityScheme="sanctum",
 *       type="http",
 *       scheme="bearer",
 *       bearerFormat="JWT",
 *       description="Enter your Sanctum Bearer token to access these endpoints"
 *  )
 */
class UserController extends Controller
{
    /**
     * @OA\Schema(
     *     schema="User",
     *     type="object",
     *     required={"id", "name", "email"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="John Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="john.doe@example.com"),
     *     @OA\Property(property="is_active", type="boolean", example=true)
     * )
     *
     * @OA\Schema(
     *     schema="NewUser",
     *     type="object",
     *     required={"name", "email", "password"},
     *     @OA\Property(property="name", type="string", example="Jane Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
     *     @OA\Property(property="password", type="string", format="password", example="password123")
     * )
     *
     * @OA\Schema(
     *     schema="UpdateUser",
     *     type="object",
     *     @OA\Property(property="name", type="string", example="Jane Doe"),
     *     @OA\Property(property="email", type="string", format="email", example="jane.doe@example.com"),
     *     @OA\Property(property="is_active", type="boolean", example=true)
     * )
     */
    public function __construct(
        protected UserService $userService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users",
     *     operationId="getUsersList",
     *     tags={"Users"},
     *     summary="Get list of users",
     *     description="Returns list of users",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/User"))
     *     )
     * )
     */
    public function index()
    {
        $users = $this->userService->all();
        return successResponse(UserResource::collection($users));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     operationId="storeUser",
     *     tags={"Users"},
     *     summary="Create a new user",
     *     description="Creates a new user in the system",
     *          security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/NewUser")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request"
     *     )
     * )
     */
    public function store(StoreUserRequest $request)
    {
        $user = $this->userService->create($request->validated());
        return successResponse(data: UserResource::make($user), statusCode: 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/users/{id}",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     summary="Get a user by ID",
     *     description="Returns a user by their ID",
     *          security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="User not found"
     *     )
     * )
     *
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
     * @OA\Put(
     *     path="/api/v1/users/{id}",
     *     operationId="updateUser",
     *     tags={"Users"},
     *     summary="Update a user by ID",
     *     description="Updates an existing user's details",
     *          security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateUser")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="User updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="User not found"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/v1/users/{id}",
     *     operationId="deleteUser",
     *     tags={"Users"},
     *     summary="Delete a user by ID",
     *     description="Deletes a user from the system",
     *          security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="User deleted successfully"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="User not found"
     *     )
     * )
     */
    public function destroy(int $id) : JsonResponse
    {
        $deleted = $this->userService->delete($id);
        if ($deleted) {
            return successResponse(message: 'User deleted successfully');
        }
        return errorResponse(message: 'User not found', statusCode: 404);
    }
}
