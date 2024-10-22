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
    /**
     * @OA\Schema(
     *     schema="Product",
     *     type="object",
     *     required={"id", "name", "description", "is_active"},
     *     @OA\Property(property="id", type="integer", example=1),
     *     @OA\Property(property="name", type="string", example="Sample Product"),
     *     @OA\Property(property="description", type="string", example="This is a sample product description."),
     *     @OA\Property(property="is_active", type="boolean", example=true),
     *     @OA\Property(property="price",type="integer",example=100)
     * )
     *
     * @OA\Schema(
     *     schema="Price",
     *     type="object",
     *     required={"type", "price"},
     *     @OA\Property(property="type", type="string", example="Normal"),
     *     @OA\Property(property="price", type="number", format="float", example=100.0)
     * )
     *
     * @OA\Schema(
     *     schema="NewProduct",
     *     type="object",
     *     required={"name", "description", "prices"},
     *     @OA\Property(property="name", type="string", example="New Product"),
     *     @OA\Property(property="description", type="string", example="Description of the new product."),
     *     @OA\Property(property="is_active", type="boolean", example=true),
     *     @OA\Property(
     * *         property="prices",
     * *         type="array",
     * *         @OA\Items(ref="#/components/schemas/Price")
     * *     )
     * )
     *
     * @OA\Schema(
     *     schema="UpdateProduct",
     *     type="object",
     *     @OA\Property(property="name", type="string", example="Updated Product Name"),
     *     @OA\Property(property="description", type="string", example="Updated product description."),
     *     @OA\Property(property="is_active", type="boolean", example=true),
     *     @OA\Property(
     *          property="prices",
     *         type="array",
     *          @OA\Items(ref="#/components/schemas/Price")
     *      )
     *  )
     */

    public function __construct(
        protected ProductService $productService
    )
    {
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products",
     *     operationId="getProductsList",
     *     tags={"Products"},
     *     summary="Get list of products",
     *     description="Returns a list of all products",
     *          security={{"sanctum": {}}},
     *     @OA\Response(
     *          response=200,
     *          description="Successful operation",
     *          @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Product"))
     *     )
     * )
     */
    public function index()
    {
        $users = $this->productService->all();
        return successResponse(ProductResource::collection($users));
    }

    /**
     * @OA\Post(
     *     path="/api/v1/products",
     *     operationId="storeProduct",
     *     tags={"Products"},
     *     summary="Create a new product with price tiers",
     *     description="Creates a new product and associates price tiers for different user types (Normal, Silver, Gold)",
     *          security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/NewProduct")
     *     ),
     *     @OA\Response(
     *          response=201,
     *          description="Product created successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request - validation error"
     *     )
     * )
     */
    public function store(StoreProductRequest $request)
    {
        $product = $this->productService->create($request->validated());
        return successResponse(data: ProductResource::make($product), statusCode: 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v1/products/{id}",
     *     operationId="getProductById",
     *     tags={"Products"},
     *     summary="Get a product by ID",
     *     description="Returns a product by its ID, including price tiers for different user types",
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
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Product not found"
     *     )
     * )
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
     * @OA\Put(
     *     path="/api/v1/products/{id}",
     *     operationId="updateProduct",
     *     tags={"Products"},
     *     summary="Update a product by ID",
     *     description="Updates an existing product, including updating its price tiers",
     *          security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(ref="#/components/schemas/UpdateProduct")
     *     ),
     *     @OA\Response(
     *          response=200,
     *          description="Product updated successfully",
     *          @OA\JsonContent(ref="#/components/schemas/Product")
     *     ),
     *     @OA\Response(
     *          response=400,
     *          description="Bad request - validation error"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Product not found"
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/v1/products/{id}",
     *     operationId="deleteProduct",
     *     tags={"Products"},
     *     summary="Delete a product by ID",
     *     description="Deletes a product, including its associated price tiers",
     *          security={{"sanctum": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *          response=204,
     *          description="Product deleted successfully"
     *     ),
     *     @OA\Response(
     *          response=404,
     *          description="Product not found"
     *     )
     * )
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
