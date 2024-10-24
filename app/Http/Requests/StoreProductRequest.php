<?php

namespace App\Http\Requests;

use App\Enums\UserTypes;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:255",
            "slug" => "nullable|string|max:255",
            "description" => "required|string",
            "image" => "required|image:jpeg,jpg,png|max:2048",
            "prices" => "required|array",
            "prices.*.price" => "required|numeric|gt:0",
            "prices.*.type" => "required|string|in:" . implode(',', UserTypes::toCases()),
            "is_active" => "required|boolean",
        ];
    }
}
