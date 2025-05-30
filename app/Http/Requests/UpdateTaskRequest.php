<?php

namespace App\Http\Requests;

class UpdateTaskRequest extends BaseTaskRequest
{
    public function rules(): array
    {
        return array_merge($this->baseRules(), [
            'secure_token' => 'required|string',
        ]);
    }
}
