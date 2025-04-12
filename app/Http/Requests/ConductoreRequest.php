<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConductoreRequest extends FormRequest
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
                'rutas_id' => 'required|exists:rutas,id',
                'nombre' => 'required|string|max:30',
                'apellido' => 'required|string|max:30',
                'email' => 'required|unique:conductores,email',
                'dui' => 'required | Integer|unique:conductores,dui',
                'telefono' => 'required |integer',
                'licencia' => ['required'],
                'TipoVehiculo' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'rutas_id.required' => 'El campo ruta es obligatorio.',
            'nombre.required' => 'El campo nombre es obligatorio.',
            'apellido.required' => 'El campo apellido es obligatorio.',
            'email.required' => 'El campo email es obligatorio.',
            'dui.required' => 'El campo dui es obligatorio.',
            'telefono.required' => 'El campo telefono es obligatorio.',
            'licencia.required' => 'El campo licencia es obligatorio.',
            'TipoVehiculo.required' => 'El campo TipoVehiculo es obligatorio.',
        ];
    }
}
