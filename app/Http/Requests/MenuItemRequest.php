<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MenuItemRequest extends FormRequest
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
        $rules =[
            'name' => 'required',
            'description' => 'nullable',
            'price' => 'required|numeric|min:0',
            'category_id' => ['required','exists:food_categories,id',],
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
            'status' => 'nullable',

        ];


        return $rules;

    }
}
