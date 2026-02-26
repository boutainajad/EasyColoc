<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreColocationRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
        ];
    }
    public function message() : array{
        return [
            'name.required' => 'le nom est obligatoire',
            'name.max' => 'le nom ne doit pas depasser 255 caracteres',
        ];
    }
}
