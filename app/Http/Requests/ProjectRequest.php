<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
// Clase de solicitud para validar los datos de un proyecto
class ProjectRequest extends FormRequest
{
    public function authorize(): bool // Método que determina si el usuario está autorizado para realizar esta solicitud
    {
        return true;
    }

    public function rules(): array // Reglas de validación para los campos del proyecto
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'status' => 'required|in:pendiente,en curso,terminado',
        ];
    }
}