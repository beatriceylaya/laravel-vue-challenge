<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class MakeCoffeeRequest extends FormRequest
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
            'type' => 'required|string|in:espresso,double_espresso,americano'
        ];
    }

    public function messages()
    {
        return [
            'type.required' => 'Please select what type of coffee you want.',
            'type.in' => 'This coffee is unavailable. Choose espresso, double espresso or americano.'
        ];
    }
}
