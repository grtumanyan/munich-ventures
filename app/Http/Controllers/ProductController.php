<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Interfaces\ProductRepositoryInterface;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{

    private ProductRepositoryInterface $productRepositoryInterface;

    public function __construct(ProductRepositoryInterface $productRepositoryInterface)
    {
        $this->productRepositoryInterface = $productRepositoryInterface;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $data = $this->productRepositoryInterface->index();
        return response()->json(ProductResource::collection($data));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        if ($this->productRepositoryInterface->store($request->validated())) {
            return response()->json([
                'success' => true,
                'message' => 'Product Created Successfully'
            ], 201);
        }
        return response()->json([
            'success' => false,
            'message' => 'Product Creation Failed'
        ], 400);
    }

    /**
     * Display the specified resource.
     */
    public function show($id): JsonResponse
    {
        $product = $this->productRepositoryInterface->getById($id);
        return response()->json(new ProductResource($product));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, $id): JsonResponse
    {
        if ($this->productRepositoryInterface->update($request->validated(), $id)) {
            return response()->json([
                'success' => true,
                'message' => 'Product Updated Successfully'
            ]);
        }
        return response()->json([
            'success' => false,
            'message' => 'Product Update Failed'
        ], 400);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse
    {
        $this->productRepositoryInterface->delete($id);
        return response()->json([
            'success' => true,
            'message' => 'Product Deleted Successfully'
        ]);
    }
}
