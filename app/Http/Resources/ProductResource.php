<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends MainResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $userType = auth()->user()->type; // Get the current user type

        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            "description" => $this->description,
            'image' => $this->image($this->image),
            'price' => $this->price($userType),
            'is_active' => $this->is_active,
        ];
    }
}
