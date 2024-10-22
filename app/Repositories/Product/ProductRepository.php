<?php

namespace App\Repositories\Product;

use App\Enums\UserTypes;
use App\Models\PriceTier;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Retrieve all products with the appropriate price based on the user's type.
     *
     * @param string $userType The type of the user (normal, gold, silver).
     * @return Collection List of products with the price based on user type.
     */
    public function getAllProductsByUserType(string $userType): Collection
    {
        return Product::isActive()->get()->map(function ($product) use ($userType) {
            // Attach the correct price based on user type
            $priceField = $this->getPriceFieldForUserType($userType);
            $product->price = $product->$priceField; // Add the correct price to the product object
            return $product;
        });
    }

    public function all(): ?Collection
    {
        return Product::isActive()->get();
    }

    public function create(array $data): Product
    {
        // Handle image upload if present
        if (isset($data['image'])) {
            $data['image'] = uploadFile($data['image'], 'products');
        }
        $product = Product::create($data);
        foreach ($data['prices'] as $price) {
            $product->prices()->create([
                'type' => UserTypes::fromName( $price['type']),
                'price' => $price['price'],
            ]);
        }
        return $product;
    }

    public function update(array $data, $id): Product
    {
        $product = Product::find($id);
        if ($product) {
            // Handle image upload if present
            if (isset($data['image'])) {
                $data['image'] = uploadFile($data['image'], 'products');
            }

            foreach ($data['prices'] as $price) {
                $priceTier = PriceTier::updateOrCreate(
                    ['product_id' => $product->id, 'type' =>   UserTypes::fromName($price['type'])],
                    ['price' => $price['price']]
                );
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
        return Product::isActive()->findOrFail($id);
    }
}
