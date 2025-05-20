<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->route('user'); // obtener id para ignorar en unique

        return [
            'username' => [
                'required',
                Rule::unique('users')->ignore($userId),
            ],
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId),
            ],
            'password' => [
                $this->isMethod('post') ? 'required' : 'nullable',
                'min:6',
            ],
            'role' => [
                'nullable',
                'string',
                'max:50',
            ],
            'id_admin' => [
                'nullable',
                'integer',
                Rule::unique('users')->ignore($userId),
            ],
        ];
    }
}
