<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\UserResource;
use App\Models\Product;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $service
    )
    {
    }

    /**
     * @OA\Post(
     *     path="/api/v1/login",
     *     operationId="loginUser",
     *     tags={"Authentication"},
     *     summary="Login user",
     *     description="Logs in a user with their email and password, and returns an access token.",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"username","password"},
     *              @OA\Property(property="username", type="string", example="normal"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          )
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="User logged in successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string", example="access_token_here")
     *          )
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request"
     *     )
     * )
     */
    public function login(LoginUserRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {

            $user = Auth::user();
            $token = $this->service->generateToken($user);

            return successResponse([
                'user' => UserResource::make($user),
                'token' => $token
            ], 'Successfully logged In');
        }

        return errorResponse('Invalid Credentials', 401);
    }

    /**
     * @OA\Post(
     *     path="/api/v1/register",
     *     operationId="registerUser",
     *     tags={"Authentication"},
     *     summary="Register a new user",
     *     description="Registers a new user with name, email, and password, and returns the created user object along with an access token.",
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password","password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="username", type="string", example="johnDoe"),
     *              @OA\Property(property="password", type="string", format="password", example="password123"),
     *              @OA\Property(property="password_confirmation", type="string", format="password", example="password123")
     *          )
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="User registered successfully",
     *          @OA\JsonContent(
     *              @OA\Property(property="user", ref="#/components/schemas/User"),
     *              @OA\Property(property="token", type="string", example="access_token_here")
     *          )
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request"
     *     )
     * )
     */
    public function register(StoreUserRequest $request)
    {

        // creating user
        $user = $this->service->create($request->validated());

        // Generate Token
        $token = $this->service->generateToken($user);

        return successResponse([
            'user' => UserResource::make($user),
            'token' => $token
        ], 'User registered successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v1/logout",
     *     operationId="logoutUser",
     *     tags={"Authentication"},
     *     summary="Logout user",
     *     description="Logs out the authenticated user by revoking their token.",
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *          response=200,
     *          description="User logged out successfully"
     *     ),
     *     @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *     )
     * )
     */
    public function logout(Request $request)
    {
        $this->service->logout();
        return successResponse(null, 'User logged out successfully');
    }
}
