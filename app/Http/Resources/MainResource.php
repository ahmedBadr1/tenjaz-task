<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MainResource extends JsonResource
{

    function get($field)
    {
        return $this->attributes->where('key', $field)->value('value') ?? $this->$field;
    }

    function discount($iso2)
    {
        return $this->discounts->filter(function ($discount) use ($iso2) {
            return $discount->country?->iso2 === $iso2;
        })->value('price') ?? $this->discounts->whereNull('country_id')->value('price');
    }

    public function image($image)
    {

        if ($image && !str_starts_with($image, 'http') ) {
            return asset('storage/' . $image);
        }
        if (empty ($image)) {
            return null; // return default image
        }
        return $image;
    }

    public function images($images)
    {
        $array = [];
        if (filled($images)) {
            foreach ($images as $image) {
                $array[] = $this->image($image);
            }
        }
        return $array;
    }
}
