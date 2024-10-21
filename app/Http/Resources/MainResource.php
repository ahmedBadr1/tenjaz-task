<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MainResource extends JsonResource
{


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

    public function price($type)
    {
        return $this->getPriceForUser($type);
    }
}
