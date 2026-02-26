<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColocationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
                    'name' => 'sometimes|string|max:255',
                    'status' => 'sometimes|in:active,cancelled',
                ];
    }
    public function messages(): array
    {
        return [
            'name.max' => 'Le nom ne doit pas depasser 255 caracteres.',
            'status.in' => 'Le statut doit etre active ou cancelled.',
        ];
    }
}
