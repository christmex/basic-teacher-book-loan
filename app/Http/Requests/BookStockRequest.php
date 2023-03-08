<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookStockRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'book_stock' => 'required|integer|min:1',
            'book_stock_description' => 'required',
        ];
    }
}
