<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class FillWaterRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|numeric|min:1|max:2000'
        ];
    }

    public function messages()
    {
        return [
            'amount.required' => 'Amount is required.',
            'amount.numeric' => 'Amount must be numeric.',
            'amount.min' => 'Amount must be at least :min ml.',
            'amount.max' => 'Water container will overflow if you add that much. Please enter a smaller amount (max :max ml).',
        ];
    }
}
