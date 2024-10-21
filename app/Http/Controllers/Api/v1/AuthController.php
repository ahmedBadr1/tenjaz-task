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
     *
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
     *
     */
    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();
        // hashing password
        $data['password'] = Hash::make($data['password']);
        // creating user
        $user = $this->service->create($data);

        // Generate Token
        $token = $this->service->generateToken($user);

        return successResponse([
            'user' => UserResource::make($user),
            'token' => $token
        ], 'User registered successfully');
    }

    public function logout(Request $request)
    {
        $this->service->logout();
        return successResponse(null, 'User logged out successfully');
    }
}
