<?php

namespace App\Http\Controllers\Api\v1;

use App\Enums\UserTypes;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;

class ProductController extends Controller
{
    public function __construct(
        protected ProductService $productService
    )
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = $this->productService->all();
        return successResponse(ProductResource::collection($users));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return successResponse(data: ProductResource::make($product), statusCode: 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $product = $this->productService->find($id);

        if (!$product) {
            return errorResponse(message: 'Product not found', statusCode: 404);
        }
        return successResponse(data: ProductResource::make($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, int $id)
    {
        $product = $this->productService->update($request->validated(), id: $id);

        if (!$product) {
            return errorResponse(message: 'Product not found', statusCode: 404);
        }

        return successResponse(data: ProductResource::make($product));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $deleted = $this->productService->delete($id);

        if (!$deleted) {
            return errorResponse(message: 'Product not found', statusCode: 404);
        }

        return successResponse(message: 'Product deleted successfully');
    }
}
