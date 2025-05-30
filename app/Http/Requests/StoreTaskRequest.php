<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;

class StoreTaskRequest extends BaseTaskRequest
{
    public function rules(): array
    {
        return $this->baseRules();
    }

    public function prepareForValidation(): void
    {
        $this->merge([
            'secure_token' => (string) Str::uuid(),
        ]);
    }
}