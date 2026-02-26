<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateColocationRequest extends FormRequest
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
