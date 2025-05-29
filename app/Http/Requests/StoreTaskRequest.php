<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreTaskRequest extends FormRequest
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
            'name' => 'required|string|min:3|max:100',
            'description' => 'required|min:10|max:5000',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Please enter a name.',
            'name.min' => 'Name must be at least 3 characters long.',
            'description.required' => 'Please enter a description.',
            'description.min' => 'Description must be at least 10 characters long.',
        ];
    }

    public function prepareForValidation()
    {
        $this->merge([
            'secure_token' => (string) Str::uuid(),
        ]);
    }

}