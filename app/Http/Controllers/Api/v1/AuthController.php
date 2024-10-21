<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }
    /**
     *
     */
    public function login()
    {
        //
    }

    /**
     *
     */
    public function register()
    {
        //
    }
}
