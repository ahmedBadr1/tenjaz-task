<?php

namespace App\Repositories\Product;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function all(): ?Collection
    {
        return Product::all();
    }

    public function create(array $data): Product
    {
        // Handle image upload if present
        if (isset($data['image'])) {
            $data['image'] = uploadFile($data['image'], 'products');
        }
        return Product::create($data);
    }

    public function update(array $data, $id): Product
    {
        $product = Product::find($id);
        if ($product) {
            // Handle image upload if present
            if (isset($data['image'])) {
                $data['image'] = uploadFile($data['image'], 'products');
            }

            $product->update($data);
        }

        return $product;
    }

    public function delete($id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function find($id): Product
    {
        return Product::findOrFail($id);
    }
}
