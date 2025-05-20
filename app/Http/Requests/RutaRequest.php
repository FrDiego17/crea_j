<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RutaRequest extends FormRequest
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
			'origen' => 'required|string|max:255',
            'descripcion' => 'required|string|max:255',
            'horarios' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'origen.required' => 'El campo origen es obligatorio.',
            'origen.string' => 'El campo origen debe ser una cadena de texto.',
            'origen.max' => 'El campo origen no puede tener más de 255 caracteres.',
        ];
    }
}
